<?php

namespace App\Http\Controllers;

use App\Models\HotelMetadata;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoomTypeController extends \App\Http\Controllers\Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of room types.
     */
    public function index(Request $request)
    {
        $query = RoomType::with('hotelMetadata');

        // Filter by hotel if provided
        if ($request->has('hotel_id') && $request->hotel_id) {
            $query->where('hotel_metadata_id', $request->hotel_id);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Filter by occupancy
        if ($request->has('max_occupancy') && $request->max_occupancy) {
            $query->where('max_occupancy', '>=', $request->max_occupancy);
        }

        // Only available rooms
        if ($request->has('available_only') && $request->available_only) {
            $query->where('available_rooms', '>', 0);
        }

        $roomTypes = $query->get();
        
        return response()->json(['status' => 'success', 'data' => $roomTypes], 200);
    }

    /**
     * Show the form for creating a new room type.
     */
    public function create(HotelMetadata $hotel)
    {
        return view('room_types.create', compact('hotel'));
    }

    /**
     * Store a newly created room type.
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

        // Handle amenities (convert array to JSON string)
        $validated['amenities'] = json_encode($validated['amenities'] ?? []);

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

        // For web form submission (if needed)
        return redirect()->route('hotels.show', $hotel->hotel_id)
            ->with('success', 'Room type created successfully');
    }

    /**
     * Display the specified room type.
     */
    public function show(RoomType $roomType)
    {
        $roomType->load('hotelMetadata');
        return response()->json(['status' => 'success', 'data' => $roomType], 200);
    }

    /**
     * Show the form for editing the specified room type.
     */
    public function edit(HotelMetadata $hotel, RoomType $roomType)
    {
        // Bypass policy check for admin users
        if (!auth()->user()->hasRole('admin')) {
            $this->authorize('update', $roomType);
        }
        
        // Ensure amenities is an array
        if (is_string($roomType->amenities)) {
            $roomType->amenities = json_decode($roomType->amenities, true);
        }
        
        return view('room_types.edit', compact('hotel', 'roomType'));
    }

    /**
     * Update the specified room type.
     */
    public function update(Request $request, HotelMetadata $hotel, RoomType $roomType)
    {
        $this->authorize('update', $roomType);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($roomType->image_url) {
                Storage::disk('public')->delete($roomType->image_url);
            }
            $path = $request->file('image')->store('room-types', 'public');
            $validated['image_url'] = $path;
        }

        $validated['amenities'] = json_encode($request->input('amenities', []));
        $roomType->update($validated);

        return redirect()->route('hotels.show', $hotel->hotel_id)
            ->with('success', 'Room type updated successfully');
    }

    /**
     * Remove the specified room type.
     */
    public function destroy(HotelMetadata $hotel, RoomType $roomType)
    {
        $this->authorize('delete', $roomType);

        if ($roomType->image_url) {
            Storage::disk('public')->delete($roomType->image_url);
        }

        $roomType->delete();

        return back()->with('success', 'Room type deleted successfully');
    }

    /**
     * Get room types by hotel.
     */
    public function getByHotel(HotelMetadata $hotel)
    {
        $roomTypes = $hotel->roomTypes()->get();

        return response()->json([
            'status' => 'success',
            'data' => $roomTypes
        ], 200);
    }

    /**
     * Check room availability for specific dates.
     */
    public function checkAvailability(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'rooms_needed' => 'required|integer|min:1',
        ]);

        // Simple availability check - you may need more complex logic
        $isAvailable = $roomType->available_rooms >= $validated['rooms_needed'];

        return response()->json([
            'status' => 'success',
            'data' => [
                'room_type' => $roomType,
                'available' => $isAvailable,
                'available_rooms' => $roomType->available_rooms,
                'requested_rooms' => $validated['rooms_needed']
            ]
        ], 200);
    }

    /**
     * Update room availability (for booking system).
     */
    public function updateAvailability(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'rooms_booked' => 'required|integer',
            'operation' => 'required|in:book,cancel', // book = decrease, cancel = increase
        ]);

        if ($validated['operation'] === 'book') {
            $newAvailability = $roomType->available_rooms - $validated['rooms_booked'];
        } else {
            $newAvailability = $roomType->available_rooms + $validated['rooms_booked'];
        }

        // Ensure availability doesn't go negative
        $newAvailability = max(0, $newAvailability);

        $roomType->update(['available_rooms' => $newAvailability]);

        return response()->json([
            'status' => 'success',
            'message' => 'Room availability updated successfully',
            'data' => [
                'room_type' => $roomType->fresh(),
                'previous_availability' => $roomType->available_rooms,
                'new_availability' => $newAvailability
            ]
        ], 200);
    }
}
