<?php

namespace App\Http\Controllers;

use App\Models\HotelMetadata;
use App\Models\Destination;
use Illuminate\Http\Request;

class HotelMetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HotelMetadata::with('destination');

        // Filter by adventure_id if provided
        if ($request->has('adventure_id') && $request->adventure_id) {
            $query->where('adventure_id', $request->adventure_id);
        }

        // Filter by province_id if provided (through destination relationship)
        if ($request->has('province_id') && $request->province_id) {
            $query->whereHas('destination', function ($q) use ($request) {
                $q->where('province_id', $request->province_id);
            });
        }

        $hotels = $query->get();
        
        return response()->json(['status' => 'success', 'data' => $hotels], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'star_rating' => 'nullable|numeric|min:0|max:5',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'website_url' => 'nullable|string|max:255',
            'map' => 'nullable|string|max:255',
            'adventure_id' => 'nullable|exists:adventures,id',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/hotels'), $imageName);
            $validated['image_url'] = url('uploads/hotels/' . $imageName);
        }

        $hotel = HotelMetadata::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel metadata created successfully',
            'data' => $hotel
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelMetadata $hotelMetadata)
    {
        $hotelMetadata->load('destination');
        return response()->json(['status' => 'success', 'data' => $hotelMetadata], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelMetadata $hotelMetadata)
    {
        $validated = $request->validate([
            'destination_id' => 'sometimes|exists:destinations,id',
            'hotel_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
        ]);

        $hotelMetadata->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel metadata updated successfully',
            'data' => $hotelMetadata
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelMetadata $hotelMetadata)
    {
        $hotelMetadata->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel metadata deleted successfully'
        ], 200);
    }

    /**
     * Get hotels by destination.
     */
    public function getByDestination(Destination $destination)
    {
        $hotels = $destination->hotels()->get();

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ], 200);
    }

    /**
     * Search hotels by name or location.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $hotels = HotelMetadata::with('destination')
            ->where('hotel_name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ], 200);
    }

    /**
     * Get hotels with room types within price range.
     */
    public function getByPriceRange(Request $request)
    {
        $minPrice = $request->get('min_price', 0);
        $maxPrice = $request->get('max_price', 999999);

        $hotels = HotelMetadata::with(['destination', 'roomTypes'])
            ->whereHas('roomTypes', function($query) use ($minPrice, $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ], 200);
    }

    /**
     * Get top-rated hotels.
     */
    public function getTopRated(Request $request)
    {
        $limit = $request->get('limit', 10);

        $hotels = HotelMetadata::with('destination')
            ->whereNotNull('rating')
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ], 200);
    }

    /**
     * Get hotels with pagination.
     */
    public function paginate(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $hotels = HotelMetadata::with('destination')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ], 200);
    }
}
