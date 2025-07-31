<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display all destinations.
     */
    public function index()
    {
        $destinations = Destination::all();
        return response()->json(['status' => 'success', 'data' => $destinations], 200);
    }

    /**
     * Display all provinces in Cambodia.
     */
    public function getProvinces()
    {
        $provinces = Destination::where('country', 'Cambodia')->get();
        return response()->json(['status' => 'success', 'data' => $provinces], 200);
    }

    /**
     * Get hotels for a given province (destination).
     */
    public function getHotels($id)
    {
        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json(['status' => 'error', 'message' => 'Province not found'], 404);
        }
        $hotels = $destination->hotels()->get()->map(function ($hotel) {
            $hotel->price = (float) $hotel->price;
            return $hotel;
        });
        return response()->json(['status' => 'success', 'data' => $hotels], 200);
    }

    /**
     * Store a new destination.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:destinations,name',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:255',
        ]);

        $destination = Destination::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Destination created successfully',
            'data' => $destination
        ], 201);
    }

    /**
     * Show a single destination.
     */
    public function show(Destination $destination)
    {
        return response()->json(['status' => 'success', 'data' => $destination], 200);
    }

    /**
     * Update a destination.
     */
    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:destinations,name,' . $destination->id,
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:255',
        ]);

        $destination->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Destination updated successfully',
            'data' => $destination
        ], 200);
    }

    /**
     * Delete a destination.
     */
    public function destroy(Destination $destination)
    {
        $destination->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Destination deleted successfully'
        ], 200);
    }
}
