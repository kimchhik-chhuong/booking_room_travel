<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use App\Models\HotelMetadata;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
            $query->where('hotel_metadata_id', $request->hotel_id);
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
            \Log::info('Booking Store Request Data:', $request->all());
            \Log::info('Auth User ID: ' . (auth()->check() ? auth()->id() : 'Not authenticated'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create($hotelId)
    {
        $hotel = HotelMetadata::findOrFail($hotelId);
        $roomTypes = $hotel->roomTypes()->where('is_available', true)->get();
        
        if ($roomTypes->isEmpty()) {
            return redirect()->back()->with('error', 'No rooms available for booking at this time.');
        }
        
        return view('hotels.book', compact('hotel', 'roomTypes'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function storeBooking(Request $request, $hotelId)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'num_guests' => 'required|integer|min:1|max:20',
                'num_rooms' => 'required|integer|min:1|max:10',
                'room_type_id' => 'required|exists:room_types,id',
                'nationality' => 'required|string|max:100',
                'special_requests' => 'nullable|string|max:1000',
            ]);
            
            // Find the hotel and room type
            $hotel = HotelMetadata::findOrFail($hotelId);
            $roomType = RoomType::findOrFail($validated['room_type_id']);
            
            // Check room availability
            $isAvailable = $this->checkRoomAvailability(
                $hotel->hotel_id, 
                $roomType->id, 
                $validated['check_in_date'], 
                $validated['check_out_date'],
                $validated['num_rooms']
            );
            
            if (!$isAvailable) {
                return back()
                    ->withInput()
                    ->with('error', 'Sorry, the selected room type is not available for the selected dates.');
            }
            
            // Calculate total price
            $checkIn = new \DateTime($validated['check_in_date']);
            $checkOut = new \DateTime($validated['check_out_date']);
            $nights = $checkIn->diff($checkOut)->days;
            $totalPrice = $roomType->price * $nights * $validated['num_rooms'];
            
            // Start database transaction
            return \DB::transaction(function () use ($validated, $hotel, $roomType, $totalPrice, $nights) {
                // Create the main booking record
                $booking = new Booking([
                    'user_id' => Auth::id(),
                    'booking_reference' => 'BOOK-' . strtoupper(Str::random(8)),
                    'booking_date' => now(),
                    'travel_date' => $validated['check_in_date'],
                    'participants' => $validated['num_guests'],
                    'total_amount' => $totalPrice,
                    'status' => 'confirmed',
                    'payment_status' => 'pending',
                ]);
                $booking->save();
                
                // Create the hotel booking record
                $hotelBooking = new HotelBooking([
                    'booking_id' => $booking->id,
                    'hotel_metadata_id' => $hotel->hotel_id,  
                    'room_type_id' => $roomType->id,
                    'check_in_date' => $validated['check_in_date'],
                    'check_out_date' => $validated['check_out_date'],
                    'num_rooms' => $validated['num_rooms'],
                    'num_guests' => $validated['num_guests'],
                    'nationality' => $validated['nationality'],
                    'price_per_night' => $roomType->price,
                    'total_hotel_price' => $totalPrice,
                    'special_requests' => $validated['special_requests'] ?? null,
                    'status' => 'confirmed',
                ]);
                $hotelBooking->save();
                
                // Redirect to payment page or booking confirmation
                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Your booking has been confirmed!');
            });
            
        } catch (\Exception $e) {
            \Log::error('Hotel booking error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'An error occurred while processing your booking. Please try again.');
        }
    }
    
    /**
     * Check room availability for the given dates
     */
    private function checkRoomAvailability($hotelId, $roomTypeId, $checkIn, $checkOut, $numRooms)
    {
        $availableRooms = RoomType::where('id', $roomTypeId)
            ->where('hotel_metadata_id', $hotelId)
            ->where('is_available', true)
            ->where('max_occupancy', '>=', request('num_guests', 1))
            ->first();
            
        if (!$availableRooms) {
            return false;
        }
        
        // Check if there are enough available rooms
        $bookedRooms = HotelBooking::where('room_type_id', $roomTypeId)
            ->where('hotel_metadata_id', $hotelId)
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
                          $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                      });
            })
            ->whereIn('status', ['confirmed', 'pending'])
            ->sum('num_rooms');
            
        $totalRooms = $availableRooms->available_rooms;
        $available = ($totalRooms - $bookedRooms) >= $numRooms;
        
        return $available;
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
            'hotel_metadata_id' => 'sometimes|exists:hotel_metadata,hotel_id',
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
