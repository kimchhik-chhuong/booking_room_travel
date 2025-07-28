<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $payments = Payment::with('booking')->paginate(10);
        return response()->json(['status' => 'success', 'data' => $payments], 200);
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed'
        ]);

        $payment = Payment::create($validated);

        return response()->json(['status' => 'success', 'data' => $payment], 201);
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load('booking');
        return response()->json(['status' => 'success', 'data' => $payment], 200);
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,completed,failed'
        ]);

        $payment->update($validated);

        return response()->json(['status' => 'success', 'data' => $payment], 200);
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['status' => 'success', 'message' => 'Payment deleted successfully'], 200);
    }
}
