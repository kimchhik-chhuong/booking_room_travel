<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;

class ProfileController extends Controller
{
    // Ensure the user is authenticated
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show profile page
    public function show()
    {
        $user = Auth::user();

        // Total bookings
        $totalBookings = Booking::where('user_id', $user->id)->count();

        // Completed bookings
        $completedBookings = Booking::where('user_id', $user->id)
                                    ->where('status', 'confirmed')
                                    ->count();

        // Total spent
        $totalSpent = Booking::where('user_id', $user->id)
                             ->sum('total_amount');

        // Recent bookings (latest 5)
        $recentBookings = Booking::where('user_id', $user->id)
                                 ->latest()
                                 ->take(5)
                                 ->get();

        // Unread messages (if you have messages table)
        $unreadMessages = 0; // Change this if you have messages functionality

        return view('profile.show', compact(
            'user', 
            'totalBookings', 
            'completedBookings', 
            'totalSpent', 
            'recentBookings',
            'unreadMessages'
        ));
    }
}
