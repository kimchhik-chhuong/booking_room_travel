<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all bookings with related user and payment
        $bookings = Booking::with(['user', 'payment', 'hotelBookings'])->get();
        return response()->json(['status' => 'success', 'data' => $bookings], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'booking_date' => 'required|date',
        ]);

        // Create booking
        $booking = Booking::create($request->only(['user_id', 'booking_date']));

        return response()->json(['status' => 'success', 'data' => $booking], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Load relationships
        $booking->load(['user', 'payment', 'hotelBookings']);
        return response()->json(['status' => 'success', 'data' => $booking], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Validate request
        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'booking_date' => 'sometimes|date',
        ]);

        // Update booking with validated data
        $booking->update($request->only(['user_id', 'booking_date']));

        return response()->json(['status' => 'success', 'data' => $booking], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(['status' => 'success', 'message' => 'Booking deleted'], 200);
    }
}
