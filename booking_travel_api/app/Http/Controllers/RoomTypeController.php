<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\HotelMetadata;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of room types for a specific hotel.
     */
    public function index($hotelId)
    {
        $roomTypes = RoomType::where('hotel_metadata_id', $hotelId)->get();

        return response()->json([
            'status' => 'success',
            'data' => $roomTypes
        ], 200);
    }

    /**
     * Store a newly created room type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_metadata_id' => 'required|exists:hotel_metadata,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'amenities' => 'nullable|array',
            'image_url' => 'nullable|string|url',
        ]);

        $roomType = RoomType::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Room type created successfully',
            'data' => $roomType
        ], 201);
    }

    /**
     * Display the specified room type.
     */
    public function show(RoomType $roomType)
    {
        return response()->json([
            'status' => 'success',
            'data' => $roomType
        ], 200);
    }

    /**
     * Update the specified room type.
     */
    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'max_occupancy' => 'sometimes|integer|min:1',
            'available_rooms' => 'sometimes|integer|min:0',
            'amenities' => 'nullable|array',
            'image_url' => 'nullable|string|url',
        ]);

        $roomType->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Room type updated successfully',
            'data' => $roomType
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
     * Get available rooms for a specific date range.
     */
    public function getAvailableRooms(Request $request, $hotelId)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Get all room types for the hotel
        $roomTypes = RoomType::where('hotel_metadata_id', $hotelId)
            ->where('available_rooms', '>', 0)
            ->get();

        // Here you would typically check against existing bookings
        // For now, we'll just return available room types

        return response()->json([
            'status' => 'success',
            'data' => $roomTypes
        ], 200);
    }
}
