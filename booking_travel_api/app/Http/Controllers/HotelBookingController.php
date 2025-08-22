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
        $query = HotelBooking::with(['hotelMetadata', 'booking', 'booking.user', 'roomType']);

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
    public function store(Request $request, $hotelId)
    {
        // Find the hotel
        $hotel = HotelMetadata::findOrFail($hotelId);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'max_occupancy' => 'required|integer|min:1',
        'available_rooms' => 'required|integer|min:0',
        'amenities' => 'nullable|array',
        'amenities.*' => 'string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle image upload if present
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('room-types', 'public');
        $validated['image_url'] = $path;
    }

    // Format amenities array
    if (isset($validated['amenities'])) {
        // Clean up the array to ensure proper formatting
        $validated['amenities'] = array_map('trim', $validated['amenities']);
        $validated['amenities'] = array_filter($validated['amenities']); // Remove empty values
        $validated['amenities'] = array_values($validated['amenities']); // Reset array keys
    } else {
        $validated['amenities'] = [];
    }

    // Set hotel ID
    $validated['hotel_metadata_id'] = $hotel->hotel_id;

    // Create the room type
    $roomType = RoomType::create($validated);
    
        // Return JSON response for API
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Room type created successfully',
                'data' => $roomType->load('hotelMetadata')
            ], 201);
        }
    
        // For web form submission
        return redirect()->route('hotels.show', $hotel->hotel_id)
            ->with('success', 'Room type created successfully');
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
            'room_type_id' => 'sometimes|exists:room_types,id',
            'num_rooms' => 'sometimes|integer|min:1',
            'num_guests' => 'sometimes|integer|min:1',
            'price_per_night' => 'sometimes|numeric|min:0',
            'total_hotel_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,confirmed,cancelled,completed',
        ]);

        // Handle room type change and availability
        if (isset($validated['room_type_id']) && $validated['room_type_id'] != $hotelBooking->room_type_id) {
            $oldRoomType = RoomType::find($hotelBooking->room_type_id);
            $newRoomType = RoomType::find($validated['room_type_id']);

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
            $roomType = RoomType::find($hotelBooking->room_type_id);
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
        $roomType = RoomType::find($hotelBooking->room_type_id);
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
        $roomType = RoomType::find($hotelBooking->room_type_id);
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
