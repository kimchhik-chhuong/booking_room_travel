<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProvinceController extends Controller
{
    /**
     * Display a listing of all provinces.
     */
    public function index()
    {
        $provinces = Province::all()->map(function ($province) {
            $province->image_url = $province->image ? asset('storage/' . $province->image) : null;
            return $province;
        });

        return response()->json([
            'status' => 'success',
            'data' => $provinces
        ], 200);
    }


    /**
     * Store a newly created province.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:provinces',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $province = new Province();
        $province->name = $validated['name'];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('provinces', 'public');
            $province->image = $imagePath;
        }

        $province->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Province created successfully',
            'data' => $province
        ], 201);
    }

    /**
     * Display the specified province.
     */
    public function show(Province $province)
    {
        // Generate full URL for image if exists
        if ($province->image) {
            $province->image_url = Storage::url($province->image);
        }

        return response()->json([
            'status' => 'success',
            'data' => $province
        ], 200);
    }

    /**
     * Update the specified province.
     */
    public function update(Request $request, Province $province)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:provinces,name,' . $province->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $province->name = $validated['name'] ?? $province->name;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($province->image) {
                Storage::disk('public')->delete($province->image);
            }

            $imagePath = $request->file('image')->store('provinces', 'public');
            $province->image = $imagePath;
        }

        $province->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Province updated successfully',
            'data' => $province
        ], 200);
    }

    /**
     * Remove the specified province.
     */
    public function destroy(Province $province)
    {
        // Delete image if exists
        if ($province->image) {
            Storage::disk('public')->delete($province->image);
        }

        $province->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Province deleted successfully'
        ], 200);
    }

    /**
     * Get all adventures for a specific province.
     */
    public function getAdventures(Province $province)
    {
        $adventures = $province->adventures()->get();

        // Generate full URLs for images
        $appUrl = config('app.url');
        $adventures->each(function ($adventure) use ($appUrl) {
            if ($adventure->image) {
                $relativeUrl = \Illuminate\Support\Facades\Storage::url($adventure->image);
                $adventure->image_url = $appUrl . $relativeUrl;
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $adventures
        ], 200);
    }

    /**
     * Search provinces by name.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $provinces = Province::where('name', 'like', "%{$query}%")
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $provinces
        ], 200);
    }
}
