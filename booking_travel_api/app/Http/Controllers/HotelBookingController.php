<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load hotel bookings with related hotel metadata and booking info
        $hotelBookings = HotelBooking::with(['hotelMetadata', 'booking'])->get();

        return response()->json(['status' => 'success', 'data' => $hotelBookings], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'hotel_metadata_id' => 'required|exists:hotel_metadata,id',
            'nights' => 'required|integer|min:1',
        ]);

        $hotelBooking = HotelBooking::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel booking created successfully',
            'data' => $hotelBooking
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelBooking $hotelBooking)
    {
        // Load related models if needed
        $hotelBooking->load(['hotelMetadata', 'booking']);

        return response()->json(['status' => 'success', 'data' => $hotelBooking], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelBooking $hotelBooking)
    {
        $validated = $request->validate([
            'booking_id' => 'sometimes|exists:bookings,id',
            'hotel_metadata_id' => 'sometimes|exists:hotel_metadata,id',
            'nights' => 'sometimes|integer|min:1',
        ]);

        $hotelBooking->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel booking updated successfully',
            'data' => $hotelBooking
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelBooking $hotelBooking)
    {
        $hotelBooking->delete();

        return response()->json(['status' => 'success', 'message' => 'Hotel booking deleted successfully'], 200);
    }
}
