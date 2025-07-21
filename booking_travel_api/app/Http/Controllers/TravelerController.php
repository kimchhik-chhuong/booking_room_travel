<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use Illuminate\Http\Request;

class TravelerController extends Controller
{
    /**
     * Display a listing of travelers.
     */
    public function index()
    {
        $travelers = Traveler::with('user')->get();
        return response()->json($travelers, 200);
    }

    /**
     * Store a newly created traveler.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $traveler = Traveler::create($validated);

        return response()->json([
            'message' => 'Traveler created successfully.',
            'data' => $traveler
        ], 201);
    }

    /**
     * Display the specified traveler.
     */
    public function show(Traveler $traveler)
    {
        $traveler->load('user');
        return response()->json($traveler, 200);
    }

    /**
     * Update the specified traveler.
     */
    public function update(Request $request, Traveler $traveler)
    {
        $validated = $request->validate([
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
        ]);

        $traveler->update($validated);

        return response()->json([
            'message' => 'Traveler updated successfully.',
            'data' => $traveler
        ], 200);
    }

    /**
     * Remove the specified traveler.
     */
    public function destroy(Traveler $traveler)
    {
        $traveler->delete();

        return response()->json([
            'message' => 'Traveler deleted successfully.'
        ], 200);
    }
}
