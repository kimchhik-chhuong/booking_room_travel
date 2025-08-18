<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use App\Models\RoomType;
use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HotelBooking::with(['hotelMetadata', 'booking', 'booking.user']);

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->whereHas('booking', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        // Filter by hotel if provided
        if ($request->has('hotel_id') && $request->hotel_id) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('check_in_from') && $request->has('check_in_to')) {
            $query->whereBetween('check_in_date', [$request->check_in_from, $request->check_in_to]);
        }

        $hotelBookings = $query->orderBy('check_in_date', 'desc')->get();

        return response()->json(['status' => 'success', 'data' => $hotelBookings], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'hotel_id' => 'required|exists:hotel_metadata,hotel_id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'room_type' => 'required|exists:room_types,id',
            'num_rooms' => 'required|integer|min:1',
            'num_guests' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'total_hotel_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        // Check room availability
        $roomType = RoomType::find($validated['room_type']);
        if ($roomType->available_rooms < $validated['num_rooms']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient rooms available',
                'available_rooms' => $roomType->available_rooms,
                'requested_rooms' => $validated['num_rooms']
            ], 400);
        }

        // Create hotel booking
        $hotelBooking = HotelBooking::create($validated);

        // Update room availability
        $roomType->decrement('available_rooms', $validated['num_rooms']);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel booking created successfully',
            'data' => $hotelBooking->load(['hotelMetadata', 'booking', 'roomType'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelBooking $hotelBooking)
    {
        $hotelBooking->load(['hotelMetadata', 'booking', 'booking.user', 'roomType']);

        return response()->json(['status' => 'success', 'data' => $hotelBooking], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelBooking $hotelBooking)
    {
        $validated = $request->validate([
            'booking_id' => 'sometimes|exists:bookings,id',
            'hotel_id' => 'sometimes|exists:hotel_metadata,hotel_id',
            'check_in_date' => 'sometimes|date|after_or_equal:today',
            'check_out_date' => 'sometimes|date|after:check_in_date',
            'room_type' => 'sometimes|exists:room_types,id',
            'num_rooms' => 'sometimes|integer|min:1',
            'num_guests' => 'sometimes|integer|min:1',
            'price_per_night' => 'sometimes|numeric|min:0',
            'total_hotel_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,confirmed,cancelled,completed',
        ]);

        // Handle room type change and availability
        if (isset($validated['room_type']) && $validated['room_type'] != $hotelBooking->room_type) {
            $oldRoomType = RoomType::find($hotelBooking->room_type);
            $newRoomType = RoomType::find($validated['room_type']);

            // Check new room availability
            if ($newRoomType->available_rooms < ($validated['num_rooms'] ?? $hotelBooking->num_rooms)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient rooms available for new room type',
                    'available_rooms' => $newRoomType->available_rooms
                ], 400);
            }

            // Return old rooms to availability
            $oldRoomType->increment('available_rooms', $hotelBooking->num_rooms);
            
            // Reserve new rooms
            $newRoomType->decrement('available_rooms', $validated['num_rooms'] ?? $hotelBooking->num_rooms);
        }

        // Handle room quantity change
        if (isset($validated['num_rooms']) && $validated['num_rooms'] != $hotelBooking->num_rooms) {
            $roomType = RoomType::find($hotelBooking->room_type);
            $roomDifference = $validated['num_rooms'] - $hotelBooking->num_rooms;

            if ($roomDifference > 0 && $roomType->available_rooms < $roomDifference) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient additional rooms available',
                    'available_rooms' => $roomType->available_rooms,
                    'additional_needed' => $roomDifference
                ], 400);
            }

            // Update availability
            $roomType->decrement('available_rooms', $roomDifference);
        }

        $hotelBooking->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel booking updated successfully',
            'data' => $hotelBooking->load(['hotelMetadata', 'booking', 'roomType'])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelBooking $hotelBooking)
    {
        // Return rooms to availability
        $roomType = RoomType::find($hotelBooking->room_type);
        $roomType->increment('available_rooms', $hotelBooking->num_rooms);

        $hotelBooking->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'Hotel booking deleted successfully'
        ], 200);
    }

    /**
     * Cancel a hotel booking
     */
    public function cancel(HotelBooking $hotelBooking)
    {
        if ($hotelBooking->status === 'cancelled') {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking is already cancelled'
            ], 400);
        }

        // Return rooms to availability
        $roomType = RoomType::find($hotelBooking->room_type);
        $roomType->increment('available_rooms', $hotelBooking->num_rooms);

        $hotelBooking->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel booking cancelled successfully',
            'data' => $hotelBooking->fresh()
        ], 200);
    }

    /**
     * Get bookings by user
     */
    public function getByUser(Request $request, $userId)
    {
        $hotelBookings = HotelBooking::with(['hotelMetadata', 'booking', 'roomType'])
            ->whereHas('booking', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('check_in_date', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $hotelBookings
        ], 200);
    }
}
