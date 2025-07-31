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
            // Fetch bookings for the authenticated user
            // Order by 'travel_date' in descending order to show most recent travel first
            // You can also include 'package' if you eager load it and have a Package model
            $bookings = Booking::where('user_id', $userId)
                               ->orderBy('travel_date', 'desc') // Order by travel_date for history
                               ->get();

            // Check if any bookings were found
            if ($bookings->isEmpty()) {
                return response()->json([
                    'message' => 'No booking history found for this user.',
                    'data' => []
                ], 200); // 200 OK, but with empty data
            }

            // Return the bookings as a JSON response
            // You might want to format the data here if needed,
            // for example, using Laravel API Resources for cleaner output.
            return response()->json([
                'message' => 'Booking history retrieved successfully.',
                'data' => $bookings
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

