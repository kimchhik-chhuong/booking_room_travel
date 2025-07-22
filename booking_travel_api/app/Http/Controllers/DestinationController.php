<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destinations = Destination::all();
        return response()->json(['status' => 'success', 'data' => $destinations], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:destinations,name',
            'description' => 'nullable|string',
        ]);

        $destination = Destination::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Destination created successfully',
            'data' => $destination
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        return response()->json(['status' => 'success', 'data' => $destination], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:destinations,name,' . $destination->id,
            'description' => 'nullable|string',
        ]);

        $destination->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Destination updated successfully',
            'data' => $destination
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
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
