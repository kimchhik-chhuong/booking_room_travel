<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;

class HotelController extends Controller
{
public function index(Request $request)
    {
        // Get hotels, optionally filter by adventure_id
        $query = Hotel::query();

        if ($request->has('adventure_id')) {
            $query->where('adventure_id', $request->adventure_id);
        }

        // Return all hotels or filtered ones
        return response()->json($query->get());
    }

     public function getHotelsByAdventureId($adventureId)
    {
        $hotels = Hotel::where('adventure_id', $adventureId)->get();

        return response()->json([
            'data' => $hotels,
        ]);
    }

    public function updateHotel(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric',
            'day' => 'sometimes|required|integer',
            'description' => 'sometimes|nullable|string',
            'province_id' => 'sometimes|required|integer|exists:provinces,id',
            'adventure_id' => 'sometimes|nullable|integer|exists:adventures,id',
        ]);

        $hotel->update($validated);

        return response()->json([
            'message' => 'Hotel updated successfully',
            'data' => $hotel,
        ]);
    }
}
