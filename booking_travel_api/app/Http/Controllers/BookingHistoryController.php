<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingHistoryController extends Controller
{
    /**
     * Get booking history for authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $bookings = Booking::with(['package', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'booking_date' => $booking->booking_date->format('Y-m-d H:i:s'),
                    'travel_date' => $booking->travel_date->format('Y-m-d'),
                    'package_name' => $booking->package->name ?? 'N/A',
                    'participants' => $booking->participants,
                    'total_amount' => $booking->total_amount,
                    'currency' => $booking->currency,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ]
        ]);
    }

    /**
     * Get detailed booking information
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $booking = Booking::with(['package', 'user', 'hotelBookings.hotelMetadata'])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'booking_date' => $booking->booking_date->format('Y-m-d H:i:s'),
                'travel_date' => $booking->travel_date->format('Y-m-d'),
                'package' => [
                    'id' => $booking->package->id,
                    'name' => $booking->package->name,
                    'description' => $booking->package->description,
                    'price' => $booking->package->price,
                ],
                'user' => [
                    'id' => $booking->user->id,
                    'name' => $booking->user->name,
                    'email' => $booking->user->email,
                ],
                'participants' => $booking->participants,
                'total_amount' => $booking->total_amount,
                'currency' => $booking->currency,
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'hotels' => $booking->hotelBookings->map(function ($hotelBooking) {
                    return [
                        'id' => $hotelBooking->id,
                        'hotel_name' => $hotelBooking->hotelMetadata->name ?? 'N/A',
                        'hotel_location' => $hotelBooking->hotelMetadata->location ?? 'N/A',
                        'nights' => $hotelBooking->nights,
                    ];
                }),
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $booking->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Get booking statistics
     */
    public function statistics()
    {
        $user = Auth::user();
        
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $confirmedBookings = Booking::where('user_id', $user->id)->where('status', 'confirmed')->count();
        $pendingBookings = Booking::where('user_id', $user->id)->where('status', 'pending')->count();
        $cancelledBookings = Booking::where('user_id', $user->id)->where('status', 'cancelled')->count();
        
        $totalSpent = Booking::where('user_id', $user->id)->where('status', 'confirmed')->sum('total_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_bookings' => $totalBookings,
                'confirmed_bookings' => $confirmedBookings,
                'pending_bookings' => $pendingBookings,
                'cancelled_bookings' => $cancelledBookings,
                'total_spent' => $totalSpent,
            ]
        ]);
    }
}
