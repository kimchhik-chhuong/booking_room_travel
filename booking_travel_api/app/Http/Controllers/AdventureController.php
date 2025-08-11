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
    public function index()
    {
        $adventures = Adventure::with('province')->get();

        // Generate full URLs for images
        $appUrl = config('app.url');
        $adventures->each(function ($adventure) use ($appUrl) {
            if ($adventure->image && Storage::disk('public')->exists($adventure->image)) {
                $adventure->image_url = $appUrl . Storage::url($adventure->image);
            } else {
                // Provide a default image URL if no image is set or file doesn't exist
                $adventure->image_url = $appUrl . '/storage/adventures/default-adventure.jpg';
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $adventures
        ], 200);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $adventure = new Adventure();
        $adventure->name = $validated['name'];
        $adventure->description = $validated['description'] ?? null;
        $adventure->province_id = $validated['province_id'];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('adventures', 'public');
            $adventure->image = $imagePath;
        }

        $adventure->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Adventure created successfully',
            'data' => $adventure
        ], 201);
    }

    /**
     * Display the specified adventure.
     */
    public function show(Adventure $adventure)
    {
        $adventure->load('province');

        // Generate full URL for image if exists
        if ($adventure->image) {
            $adventure->image_url = Storage::url($adventure->image);
        }

        return response()->json([
            'status' => 'success',
            'data' => $adventure
        ], 200);
    }

    /**
     * Update the specified adventure.
     */
    public function update(Request $request, Adventure $adventure)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'province_id' => 'sometimes|exists:provinces,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $adventure->name = $validated['name'] ?? $adventure->name;
        $adventure->description = $validated['description'] ?? $adventure->description;
        $adventure->province_id = $validated['province_id'] ?? $adventure->province_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($adventure->image) {
                Storage::disk('public')->delete($adventure->image);
            }

            $imagePath = $request->file('image')->store('adventures', 'public');
            $adventure->image = $imagePath;
        }

        $adventure->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Adventure updated successfully',
            'data' => $adventure
        ], 200);
    }

    /**
     * Remove the specified adventure.
     */
    public function destroy(Adventure $adventure)
    {
        // Delete image if exists
        if ($adventure->image) {
            Storage::disk('public')->delete($adventure->image);
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

        // Generate full URLs for images
        $appUrl = config('app.url');
        $adventures->each(function ($adventure) use ($appUrl) {
            if ($adventure->image && Storage::disk('public')->exists($adventure->image)) {
                $adventure->image_url = $appUrl . Storage::url($adventure->image);
            } else {
                // Provide a default image URL if no image is set or file doesn't exist
                $adventure->image_url = $appUrl . '/storage/adventures/default-adventure.jpg';
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $adventures
        ], 200);
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
        $adventures->each(function ($adventure) {
            if ($adventure->image) {
                $adventure->image_url = Storage::url($adventure->image);
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
        $adventures->each(function ($adventure) {
            if ($adventure->image) {
                $adventure->image_url = Storage::url($adventure->image);
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $adventures
        ], 200);
    }
}
