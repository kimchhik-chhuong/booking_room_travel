<?php

namespace App\Http\Controllers;

use App\Models\HotelMetadata;
use App\Models\Province;
use Illuminate\Http\Request;

class HotelMetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HotelMetadata::with(['province', 'roomTypes']);

        // Filter by adventure_id if provided
        if ($request->has('adventure_id') && $request->adventure_id) {
            $query->where('adventure_id', $request->adventure_id);
        }

        // Filter by province_id if provided
        if ($request->has('province_id') && $request->province_id) {
            $query->where('province_id', $request->province_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        } else {
            $query->active(); // Only active hotels by default
        }

        // Filter by location (hotels with coordinates)
        if ($request->has('with_location') && $request->with_location) {
            $query->withLocation();
        }

        $hotels = $query->get();
        
        return response()->json(['status' => 'success', 'data' => $hotels], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        $adventures = \App\Models\Adventure::all();
        return view('hotels.create', compact('provinces', 'adventures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'adventure_id' => 'nullable|exists:adventures,id',
            'star_rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'contact_phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'website_url' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'amenities' => 'nullable|string',
        ]);

        try {
            // Handle main image upload
            $imagePath = $request->file('image')->store('hotels', 'public');
            
            // Prepare additional images
            $additionalImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $additionalImages[] = $image->store('hotels/additional', 'public');
                }
            }

            // Create hotel
            $hotel = HotelMetadata::create([
                'name' => $validated['name'],
                'province_id' => $validated['province_id'],
                'adventure_id' => $validated['adventure_id'] ?? null,
                'star_rating' => $validated['star_rating'],
                'description' => $validated['description'],
                'contact_phone' => $validated['contact_phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'website_url' => $validated['website_url'] ?? null,
                'image_url' => $imagePath,
                'additional_images' => !empty($additionalImages) ? json_encode($additionalImages) : null,
                'amenities' => $validated['amenities'] ? json_encode(array_map('trim', explode(',', $validated['amenities']))) : null,
                'status' => 'active',
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Hotel created successfully',
                    'data' => $hotel->load(['province', 'roomTypes'])
                ], 201);
            }

            return redirect()->route('hotels.index')
                ->with('success', 'Hotel created successfully');
                
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error creating hotel: ' . $e->getMessage()
                ], 400);
            }

            return back()->withInput()
                ->with('error', 'Error creating hotel: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hotel = HotelMetadata::with(['province', 'roomTypes'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $hotel], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $hotel = HotelMetadata::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'star_rating' => 'nullable|numeric|min:0|max:5',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'amenities' => 'nullable|array',
            'contact_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website_url' => 'nullable|string|max:255',
            'map' => 'nullable|string|max:255',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'adventure_id' => 'nullable|exists:adventures,id',
            'province_id' => 'nullable|exists:provinces,id',
            'status' => 'nullable|in:active,inactive,maintenance',
        ]);

        $hotel->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel metadata updated successfully',
            'data' => $hotel->load(['province', 'roomTypes'])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hotel = HotelMetadata::findOrFail($id);
        $hotel->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel metadata deleted successfully'
        ], 200);
    }

    /**
     * Get hotels by province.
     */
    public function getByProvince(Province $province)
    {
        $hotels = $province->hotels()->active()->with('roomTypes')->get();

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

        $hotels = HotelMetadata::with(['province', 'roomTypes'])
            ->active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
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

        $hotels = HotelMetadata::with(['province', 'roomTypes'])
            ->active()
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

        $hotels = HotelMetadata::with(['province', 'roomTypes'])
            ->active()
            ->whereNotNull('star_rating')
            ->orderBy('star_rating', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ], 200);
    }

    /**
     * Get hotels with location coordinates.
     */
    public function getWithLocation(Request $request)
    {
        $hotels = HotelMetadata::with(['province', 'roomTypes'])
            ->active()
            ->withLocation()
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
        $hotels = HotelMetadata::with(['province', 'roomTypes'])
            ->active()
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ], 200);
    }
}
