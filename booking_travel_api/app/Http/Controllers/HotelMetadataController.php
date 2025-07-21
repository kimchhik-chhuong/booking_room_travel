<?php

namespace App\Http\Controllers;

use App\Models\HotelMetadata;
use Illuminate\Http\Request;

class HotelMetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = HotelMetadata::all();
        return response()->json(['status' => 'success', 'data' => $hotels], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'hotel_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $hotel = HotelMetadata::create($validated);

        return response()->json(['status' => 'success', 'message' => 'Hotel metadata created successfully', 'data' => $hotel], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelMetadata $hotelMetadata)
    {
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
            'price' => 'sometimes|numeric|min:0',
        ]);

        $hotelMetadata->update($validated);

        return response()->json(['status' => 'success', 'message' => 'Hotel metadata updated successfully', 'data' => $hotelMetadata], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelMetadata $hotelMetadata)
    {
        $hotelMetadata->delete();

        return response()->json(['status' => 'success', 'message' => 'Hotel metadata deleted successfully'], 200);
    }
}
