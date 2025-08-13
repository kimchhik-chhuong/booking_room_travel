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
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/adventures'), $imageName);
            $adventure->image_url = 'uploads/adventures/' . $imageName;
        }

        $adventure->save();

        // Ensure the image_url is returned as full URL in response
        if ($adventure->image_url) {
            $appUrl = 'http://localhost:8000';
            $adventure->image_url = $appUrl . '/' . $adventure->image_url;
        } else {
            $appUrl = 'http://localhost:8000';
            $adventure->image_url = $appUrl . '/uploads/adventures/default-adventure.jpg';
        }

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
        if ($adventure->image_url) {
            $appUrl = 'http://localhost:8000';
            if (file_exists(public_path($adventure->image_url))) {
                $adventure->image_url = $appUrl . '/' . $adventure->image_url;
            } else {
                $adventure->image_url = $appUrl . '/uploads/adventures/default-adventure.jpg';
            }
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
            if ($adventure->image_url && file_exists(public_path($adventure->image_url))) {
                unlink(public_path($adventure->image_url));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/adventures'), $imageName);
            $adventure->image_url = 'uploads/adventures/' . $imageName;
        }

        $adventure->save();

        // Ensure the image_url is returned as full URL in response
        if ($adventure->image_url) {
            $appUrl = 'http://localhost:8000';
            $adventure->image_url = $appUrl . '/' . $adventure->image_url;
        }

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
