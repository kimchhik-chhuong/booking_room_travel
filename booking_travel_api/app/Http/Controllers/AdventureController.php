<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdventureController extends Controller
{
    /**
     * Display a listing of all adventures.
     */
    public function index(Request $request)
    {
        $query = Adventure::with('province');

        // Apply province filter if provided
        if ($request->has('province_id') && $request->province_id) {
            $query->where('province_id', $request->province_id);
        }

        // For API response
        if ($request->wantsJson() || $request->ajax()) {
            $adventures = $query->get()->map(function($adventure) {
                // Ensure we have the correct image URL format
                $imageUrl = $adventure->image_url;
                if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    // If it's a relative path, make it a full URL
                    if (strpos($imageUrl, 'storage/') === 0) {
                        $imageUrl = asset($imageUrl);
                    } else {
                        $imageUrl = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                }
                
                return array_merge($adventure->toArray(), [
                    'image_url' => $imageUrl,
                    'image_path' => $imageUrl // For backward compatibility
                ]);
            });

            return response()->json([
                'status' => 'success',
                'data' => $adventures
            ], 200);
        }

        // For web response
        $adventures = $query->paginate(12);
        $provinces = Province::all();

        return view('adventures.index', [
            'adventures' => $adventures,
            'provinces' => $provinces,
            'selectedProvinceId' => $request->province_id
        ]);
    }

    /**
     * Show the form for creating a new adventure.
     */
    public function create(Request $request)
    {
        $provinces = Province::all();
        $provinceId = $request->input('province_id');
        
        return view('adventures.create', [
            'provinces' => $provinces,
            'selectedProvinceId' => $provinceId
        ]);
    }

    /**
     * Store a newly created adventure.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'province_id' => 'required|exists:provinces,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle image upload
            $imagePath = $request->file('image')->store('public/adventures');
            // Store the relative path without 'public/' prefix
            $relativePath = str_replace('public/', '', $imagePath);

            // Create adventure
            $adventure = Adventure::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'province_id' => $validated['province_id'],
                'image_url' => $relativePath,
                'status' => 'active',
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Adventure created successfully',
                    'data' => $adventure->load('province')
                ], 201);
            }

            return redirect()->route('adventures.index')
                ->with('success', 'Adventure created successfully');
                
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create adventure',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create adventure: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified adventure.
     */
    public function show(Request $request, $id)
    {
        $adventure = Adventure::with('province')->findOrFail($id);
        
        // For API response
        if ($request->wantsJson() || $request->ajax()) {
            // Ensure we have the correct image URL format
            $imageUrl = $adventure->image_url;
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                // If it's a relative path, make it a full URL
                if (strpos($imageUrl, 'storage/') === 0) {
                    $imageUrl = asset($imageUrl);
                } else {
                    $imageUrl = asset('storage/' . ltrim($imageUrl, '/'));
                }
            }
            
            $adventureArray = $adventure->toArray();
            $adventureArray['image_url'] = $imageUrl;
            $adventureArray['image_path'] = $imageUrl; // For backward compatibility
            
            return response()->json([
                'status' => 'success',
                'data' => $adventureArray
            ], 200);
        }

        // For web response
        return view('adventures.show', ['adventure' => $adventure]);
    }

    /**
     * Show the form for editing the specified adventure.
     */
    public function edit(Adventure $adventure)
    {
        $provinces = Province::all();
        
        return view('adventures.edit', [
            'adventure' => $adventure,
            'provinces' => $provinces,
            'pageTitle' => 'Edit Adventure: ' . $adventure->name
        ]);
    }

    /**
     * Update the specified adventure.
     */
    public function update(Request $request, Adventure $adventure)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'province_id' => 'required|exists:provinces,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($adventure->image_url && Storage::disk('public')->exists($adventure->image_url)) {
                    Storage::disk('public')->delete($adventure->image_url);
                }
                
                $imagePath = $request->file('image')->store('public/adventures');
                $relativePath = str_replace('public/', '', $imagePath);
                $validated['image_url'] = $relativePath;
            }

            $adventure->update($validated);

            // For API responses
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Adventure updated successfully',
                    'data' => $adventure->load('province')
                ]);
            }

            // For web requests, redirect to show page with success message
            return redirect()->route('adventures.show', $adventure)
                ->with('success', 'Adventure updated successfully');
                
        } catch (\Exception $e) {
            // For API responses
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update adventure: ' . $e->getMessage()
                ], 500);
            }

            // For web requests, redirect back with error message
            return back()->withInput()
                ->with('error', 'Failed to update adventure: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified adventure.
     */
    public function destroy(Adventure $adventure)
    {
        // Delete image if exists
        if ($adventure->image_url && file_exists(public_path($adventure->image_url))) {
            unlink(public_path($adventure->image_url));
        }

        $adventure->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Adventure deleted successfully'
        ], 200);
    }

    /**
     * Get adventures by province.
     */
    public function getByProvince(Province $province)
    {
        $adventures = $province->adventures()->with('province')->get();

        // Generate full URLs for images with port 8000
        $appUrl = 'http://localhost:8000';
        $adventures->each(function ($adventure) use ($appUrl) {
            if ($adventure->image_url && file_exists(public_path($adventure->image_url))) {
                $adventure->image_url = $appUrl . '/' . $adventure->image_url;
            } else {
                // Provide a default image URL if no image is set or file doesn't exist
                $adventure->image_url = $appUrl . '/uploads/adventures/default-adventure.jpg';
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $adventures
        ], 200);
    }

    /**
     * Display adventures by province.
     */
    public function byProvince(Province $province)
    {
        $adventures = Adventure::where('province_id', $province->id)
            ->where('status', 'active')
            ->with('province')
            ->paginate(12);

        // For API responses
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => $adventures
            ]);
        }

        // For web requests
        return view('adventures.index', [
            'adventures' => $adventures,
            'provinces' => Province::all(),
            'selectedProvinceId' => $province->id,
            'pageTitle' => "Adventures in {$province->name}"
        ]);
    }

    /**
     * Search adventures by name.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $adventures = Adventure::with('province')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        // Generate full URLs for images
        $appUrl = config('app.url');
        $adventures->each(function ($adventure) use ($appUrl) {
            if ($adventure->image_url && file_exists(public_path($adventure->image_url))) {
                $adventure->image_url = $appUrl . '/' . $adventure->image_url;
            } else {
                $adventure->image_url = $appUrl . '/uploads/adventures/default-adventure.jpg';
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $adventures
        ], 200);
    }

    /**
     * Get adventures with pagination.
     */
    public function paginate(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $adventures = Adventure::with('province')->paginate($perPage);

        // Generate full URLs for images
        $appUrl = config('app.url');
        $adventures->each(function ($adventure) use ($appUrl) {
            if ($adventure->image_url && file_exists(public_path($adventure->image_url))) {
                $adventure->image_url = $appUrl . '/' . $adventure->image_url;
            } else {
                $adventure->image_url = $appUrl . '/uploads/adventures/default-adventure.jpg';
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $adventures
        ], 200);
    }
}
