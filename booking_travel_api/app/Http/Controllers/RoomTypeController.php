<?php

namespace App\Http\Controllers;

use App\Models\HotelMetadata;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
     * Get available rooms for a specific hotel
     *
     * @param int $hotelId
     * @return \Illuminate\Http\JsonResponse
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
            \Log::info('Fetched available rooms', [
                'hotel_id' => $hotelId,
                'room_count' => $rooms->count()
            ]);

            return response()->json($rooms);
            
        } catch (\Exception $e) {
            // Log the full error with context
            \Log::error('Error in getAvailableRooms', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'hotel_id' => $hotelId ?? 'null',
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch available rooms',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => []
            ], 500);
        }
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HotelMetadata  $hotel
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, HotelMetadata $hotel)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'max_occupancy' => 'required|integer|min:1',
                'available_rooms' => 'required|integer|min:0',
                'amenities' => 'nullable|array',
                'amenities.*' => 'string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle file upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('rooms', 'public');
            }

            // Create the room type
            $roomType = new RoomType([
                'hotel_metadata_id' => $hotel->hotel_id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'max_occupancy' => $validated['max_occupancy'],
                'available_rooms' => $validated['available_rooms'],
                'amenities' => $validated['amenities'] ?? [],
                'image_url' => $imagePath,
                'is_available' => true
            ]);

            $roomType->save();

            return redirect('http://localhost:8000/hotels/' . $hotel->hotel_id)
                ->with('success', 'Room type created successfully');
                
        } catch (\Exception $e) {
            Log::error('Error creating room type: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->withInput()
                ->with('error', 'Error creating room type: ' . $e->getMessage());
        }
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
        // Ensure the room type belongs to the specified hotel
        if ($roomType->hotel_metadata_id != $hotel->hotel_id) {
            abort(404, 'Room type not found for this hotel');
        }

        // Decode amenities if it's a JSON string
        if (is_string($roomType->amenities)) {
            $roomType->amenities = json_decode($roomType->amenities, true) ?: [];
        }

        return view('room_types.edit', compact('hotel', 'roomType'));
    }

    /**
     * Update the specified room type.
     */
    public function update(Request $request, HotelMetadata $hotel, RoomType $roomType)
    {
        // Ensure the room type belongs to the hotel
        if ($roomType->hotel_metadata_id !== $hotel->hotel_id) {
            abort(403, 'Room type does not belong to this hotel.');
        }
    
        // Optional: use policy if you set it up
        // $this->authorize('update', $roomType);
    
        \Log::info('Update Room Type Request Data:', [
            'request_data' => $request->all(),
            'room_type_id' => $roomType->id,
            'hotel_id' => $hotel->hotel_id
        ]);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            if ($request->hasFile('image')) {
                if ($roomType->image_url) {
                    Storage::disk('public')->delete($roomType->image_url);
                }
                $validated['image_url'] = $request->file('image')->store('room-types', 'public');
            }
    
            if (isset($validated['amenities']) && is_array($validated['amenities'])) {
                $validated['amenities'] = json_encode($validated['amenities']);
            }
    
            $updated = $roomType->update($validated);
    
            if ($updated) {
                \Log::info('Room type updated successfully', [
                    'room_type_id' => $roomType->id,
                    'new_values' => $roomType->fresh()->toArray()
                ]);
            } else {
                \Log::error('Failed to update room type', [
                    'room_type_id' => $roomType->id,
                    'validated_data' => $validated
                ]);
            }
    
            return redirect()->route('hotels.show', $hotel->hotel_id)
                ->with('success', 'Room type updated successfully');
    
        } catch (\Exception $e) {
            \Log::error('Error updating room type: ' . $e->getMessage(), [
                'exception' => $e,
                'room_type_id' => $roomType->id
            ]);
    
            return back()->withInput()
                ->with('error', 'Error updating room type: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified room type.
     *
     * @param  \App\Models\HotelMetadata  $hotel
     * @param  \App\Models\RoomType  $roomType
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelMetadata $hotel, RoomType $roomType)
    {
        try {
            $user = auth()->user();
            
            // Verify the room type belongs to the specified hotel
            if ($roomType->hotel_metadata_id != $hotel->hotel_id) {
                abort(404);
            }

            // Check if user is admin or hotel owner
            if (!$user->hasRole('admin') && $hotel->user_id !== $user->id) {
                return back()->with('error', 'You are not authorized to delete this room type.');
            }

            // Delete image if exists
            if ($roomType->image_url) {
                Storage::disk('public')->delete($roomType->image_url);
            }

            $roomType->delete();

            return redirect()->route('hotels.show', $hotel->hotel_id)
                ->with('success', 'Room type deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Error deleting room type: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Error deleting room type. Please try again.');
        }
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
