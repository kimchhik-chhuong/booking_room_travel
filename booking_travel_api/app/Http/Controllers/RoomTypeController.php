<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\HotelMetadata;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
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
     * Store a newly created room type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_metadata_id' => 'required|exists:hotel_metadata,hotel_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'available_rooms' => 'required|integer|min:0',
            'amenities' => 'nullable|array',
            'image_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/rooms'), $imageName);
            $validated['image_url'] = url('uploads/rooms/' . $imageName);
        }

        $roomType = RoomType::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Room type created successfully',
            'data' => $roomType->load('hotelMetadata')
        ], 201);
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
     * Update the specified room type.
     */
    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'hotel_metadata_id' => 'sometimes|exists:hotel_metadata,hotel_id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'max_occupancy' => 'sometimes|integer|min:1|max:10',
            'available_rooms' => 'sometimes|integer|min:0',
            'amenities' => 'nullable|array',
            'image_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/rooms'), $imageName);
            $validated['image_url'] = url('uploads/rooms/' . $imageName);
        }

        $roomType->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Room type updated successfully',
            'data' => $roomType->load('hotelMetadata')
        ], 200);
    }

    /**
     * Remove the specified room type.
     */
    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Room type deleted successfully'
        ], 200);
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
