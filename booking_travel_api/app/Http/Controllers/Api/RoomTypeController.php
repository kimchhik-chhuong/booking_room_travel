<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelMetadata;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RoomTypeController extends Controller
{
    /**
     * Get available rooms for a hotel
     *
     * @param int $hotelId
     * @return JsonResponse
     */
    public function getAvailableRooms($hotelId)
    {
        try {
            // First, check if the hotel exists
            $hotel = HotelMetadata::find($hotelId);
            
            if (!$hotel) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hotel not found',
                    'data' => []
                ], 404);
            }

            // Get available rooms for the hotel
            $rooms = RoomType::where('hotel_metadata_id', $hotelId)
                ->where('is_available', true)
                ->where('available_rooms', '>', 0)
                ->get()
                ->map(function($room) {
                    return [
                        'id' => $room->id,
                        'name' => $room->name,
                        'description' => $room->description,
                        'price' => (float)$room->price,
                        'max_occupancy' => (int)$room->max_occupancy,
                        'available_rooms' => (int)$room->available_rooms,
                        'amenities' => is_string($room->amenities) 
                            ? json_decode($room->amenities, true) 
                            : ($room->amenities ?? []),
                        'image_url' => $room->image_url ? asset('storage/' . $room->image_url) : null
                    ];
                });

            // Log the response for debugging
            Log::info('Fetched available rooms', [
                'hotel_id' => $hotelId,
                'room_count' => $rooms->count()
            ]);

            return response()->json($rooms);
            
        } catch (\Exception $e) {
            // Log the full error with context
            Log::error('Error in getAvailableRooms', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'hotel_id' => $hotelId ?? 'null'
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch available rooms',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => []
            ], 500);
        }
    }
}
