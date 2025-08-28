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
    $user = Auth::user(); // get logged-in user

    // Optional: recent bookings and stats
    $recentBookings = $user->bookings()->latest()->take(5)->get();
    $totalBookings = $user->bookings()->count();
    $completedBookings = $user->bookings()->where('status', 'completed')->count();
    $totalSpent = $user->bookings()->sum('total_amount');
    $unreadMessages = $user->messages()->where('read', false)->count();

    return view('profile.show', compact(
        'user', 
        'recentBookings', 
        'totalBookings', 
        'completedBookings', 
        'totalSpent', 
        'unreadMessages'
    ));
}
}
