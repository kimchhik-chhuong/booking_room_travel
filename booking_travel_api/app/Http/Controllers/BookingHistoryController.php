<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking; // Make sure this is the correct path to your Booking model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // For authenticating the user
use Illuminate\Support\Facades\Log; // For logging errors

class BookingHistoryController extends Controller
{
    /**
     * Get the booking history for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required to view booking history.'
            ], 401); // 401 Unauthorized
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        try {
            // Fetch bookings for the authenticated user, eager-loading the 'package' relationship
            // Eager loading is crucial to access the package's details without making separate queries.
            $bookings = Booking::with('package')
                               ->where('user_id', $userId)
                               ->orderBy('travel_date', 'desc') // Order by travel_date for history
                               ->get();

            // Check if any bookings were found
            if ($bookings->isEmpty()) {
                return response()->json([
                    'message' => 'No booking history found for this user.',
                    'data' => []
                ], 200); // 200 OK, but with empty data
            }

            // Transform the bookings data to match the Flutter app's expectations
            $formattedBookings = $bookings->map(function ($booking) {
                return [
                    // Ensure the column names match your database schema
                    'booking_id' => $booking->booking_reference, // Use booking_reference as booking_id
                    'hotel_name' => $booking->package->name ?? 'N/A', // Access package name
                    'location' => $booking->package->location ?? 'N/A', // Access package location
                    'start_date' => $booking->travel_date ? $booking->travel_date->toDateString() : 'N/A', // Use travel_date
                    'end_date' => 'N/A', // Assuming you don't have an end_date, you can add it if needed
                    'total_price' => $booking->total_amount,
                ];
            });

            // Return the formatted bookings as a JSON response
            return response()->json([
                'message' => 'Booking history retrieved successfully.',
                'data' => $formattedBookings
            ], 200); // 200 OK
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error fetching booking history: ' . $e->getMessage(), [
                'user_id' => $userId,
                'exception' => $e
            ]);

            return response()->json([
                'message' => 'An error occurred while retrieving booking history.',
                'error' => 'Server error. Please try again later.' // Generic error for client
            ], 500); // 500 Internal Server Error
        }
    }
}
