<?php

namespace App\Http\Controllers;

use App\Models\CambodiaTrip;
use Illuminate\Http\Request;

class CambodiaTripController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
        ]);

        $trip = CambodiaTrip::create([
            'country_name' => 'Cambodia',
            'hotel_name' => $validated['hotel_name'],
        ]);

        return response()->json($trip, 201);
    }
}
