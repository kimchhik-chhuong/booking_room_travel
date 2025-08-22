<?php

namespace App\Http\Controllers;

use App\Models\HotelMetadata;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        // Handle sorting
        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy(DB::raw('(SELECT MIN(price) FROM room_types WHERE room_types.hotel_metadata_id = hotel_metadata.hotel_id)'), 'asc');
                break;
            case 'price_desc':
                $query->orderBy(DB::raw('(SELECT MAX(price) FROM room_types WHERE room_types.hotel_metadata_id = hotel_metadata.hotel_id)'), 'desc');
                break;
            case 'rating':
                $query->orderBy('star_rating', 'desc');
                break;
            default: // name_asc
                $query->orderBy('name', 'asc');
        }

        $hotels = $query->paginate(12);
        $provinces = \App\Models\Province::orderBy('name')->get();
        
        // Return view for web requests, JSON for API requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'data' => $hotels], 200);
        }
        
        return view('hotels.index', [
            'hotels' => $hotels,
            'provinces' => $provinces,
        ]);
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
            'star_rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'contact_phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'website' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
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

            // Process amenities
            $amenities = $request->input('amenities', []);
            
            // Create hotel
            $hotel = HotelMetadata::create([
                'name' => $validated['name'],
                'province_id' => $validated['province_id'],
                'star_rating' => $validated['star_rating'],
                'description' => $validated['description'],
                'contact_phone' => $validated['contact_phone'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'],
                'website_url' => $validated['website'] ?? null,
                'image_url' => $imagePath,
                'images' => !empty($additionalImages) ? json_encode($additionalImages) : null,
                'amenities' => !empty($amenities) ? json_encode($amenities) : null,
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
    public function show(HotelMetadata $hotel)
    {
        try {
            $hotel->load(['province', 'roomTypes']);
            
            // Convert images and amenities JSON to array for the view
            $hotel->additional_images = $hotel->images ? json_decode($hotel->images, true) : [];
            $hotel->amenities = $hotel->amenities ? (is_array($hotel->amenities) ? $hotel->amenities : json_decode($hotel->amenities, true)) : [];
                
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $hotel
                ]);
            }
            
            return view('hotels.show', compact('hotel'));
            
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error retrieving hotel: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('packages.index')
                ->with('error', 'Error retrieving hotel details');
        }
    }

    /**
     * Display the specified resource.
     */
    public function showJson($id)
    {
        $hotel = HotelMetadata::with(['province', 'roomTypes'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $hotel], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelMetadata $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'adventure_id' => 'nullable|exists:adventures,id',
            'star_rating' => 'required|integer|min:1|max:5',
            'contact_phone' => 'required|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'removed_images' => 'nullable|string',
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($hotel->image_url) {
                Storage::disk('public')->delete($hotel->image_url);
            }
            $path = $request->file('image')->store('hotels', 'public');
            $validated['image_url'] = $path;
        }

        // Handle additional images
        $additionalImages = [];
        
        // 1. Keep existing images that weren't removed
        if ($request->has('existing_images')) {
            $additionalImages = $request->input('existing_images');
            
            // Remove images that were marked for deletion
            if ($request->has('removed_images') && !empty($request->removed_images)) {
                $removedImages = explode(',', $request->removed_images);
                foreach ($removedImages as $removedImage) {
                    if (($key = array_search($removedImage, $additionalImages)) !== false) {
                        // Delete the image file from storage
                        Storage::disk('public')->delete($removedImage);
                        unset($additionalImages[$key]);
                    }
                }
                // Re-index array after unset
                $additionalImages = array_values($additionalImages);
            }
        }
        
        // 2. Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('hotels/additional', 'public');
                $additionalImages[] = $path;
            }
        }
        
        // Update the images field
        $validated['images'] = !empty($additionalImages) ? json_encode($additionalImages) : null;

        // Handle amenities
        $validated['amenities'] = $request->has('amenities') ? json_encode($request->input('amenities')) : json_encode([]);
        
        // Map website field to website_url
        $validated['website_url'] = $validated['website'] ?? null;
        unset($validated['website']);

        $hotel->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Hotel updated successfully',
                'data' => $hotel->load(['province', 'adventure'])
            ]);
        }

        return redirect()->route('hotels.show', $hotel->hotel_id)
            ->with('success', 'Hotel updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelMetadata $hotel)
    {
        // Delete the hotel's image if it exists
        if ($hotel->image_url) {
            Storage::disk('public')->delete($hotel->image_url);
        }

        $hotel->delete();

        return redirect()->route('hotels.index')
            ->with('success', 'Hotel deleted successfully');
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HotelMetadata $hotel)
    {
        $provinces = Province::all();
        $adventures = \App\Models\Adventure::all();
        return view('hotels.edit', compact('hotel', 'provinces', 'adventures'));
    }
}
