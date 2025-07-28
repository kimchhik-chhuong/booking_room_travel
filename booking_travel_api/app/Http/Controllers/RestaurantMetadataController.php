<?php

namespace App\Http\Controllers;

use App\Models\RestaurantMetadata;
use Illuminate\Http\Request;

class RestaurantMetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RestaurantMetadata::with('destination');

        // Optional search filter by restaurant name
        if ($request->has('search')) {
            $query->where('restaurant_name', 'like', '%' . $request->search . '%');
        }

        // Optional filter by destination_id
        if ($request->has('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        $restaurants = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $restaurants
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'restaurant_name' => 'required|string|max:255',
            'cuisine_type' => 'required|string|max:100',
        ]);

        $restaurant = RestaurantMetadata::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant metadata created successfully',
            'data' => $restaurant
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RestaurantMetadata $restaurantMetadata)
    {
        $restaurantMetadata->load('destination');
        return response()->json([
            'status' => 'success',
            'data' => $restaurantMetadata
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RestaurantMetadata $restaurantMetadata)
    {
        $validated = $request->validate([
            'restaurant_name' => 'sometimes|string|max:255',
            'cuisine_type' => 'sometimes|string|max:100',
        ]);

        $restaurantMetadata->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant metadata updated successfully',
            'data' => $restaurantMetadata
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RestaurantMetadata $restaurantMetadata)
    {
        $restaurantMetadata->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant metadata deleted successfully'
        ], 200);
    }
}
