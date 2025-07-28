<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use App\Models\Message;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $totalBookings = Booking::count();
        $newCustomers = User::where('role', 'customer')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $totalEarnings = Booking::where('status', 'confirmed')
            ->sum('total_amount');

        // Get booking trends for chart
        $bookingTrends = $this->getBookingTrends();
        
        // Get top destinations
        $topDestinations = $this->getTopDestinations();
        
        // Get recent packages
        $packages = Package::with('destination')
            ->latest()
            ->take(3)
            ->get();
            
        // Get recent messages
        $messages = Message::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        // Get upcoming trips
        $upcomingTrips = Trip::with(['destination', 'participants'])
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date')
            ->take(4)
            ->get();
            
        // Get unread messages count
        $unreadMessages = Message::where('is_read', false)->count();

        return view('dashboard', compact(
            'totalBookings',
            'newCustomers', 
            'totalEarnings',
            'bookingTrends',
            'topDestinations',
            'packages',
            'messages',
            'upcomingTrips',
            'unreadMessages'
        ));
    }

    private function getBookingTrends()
    {
        return Booking::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as revenue')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getTopDestinations()
    {
        return DB::table('bookings')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->join('destinations', 'packages.destination_id', '=', 'destinations.id')
            ->select('destinations.name', 'destinations.country', DB::raw('COUNT(*) as booking_count'))
            ->where('bookings.status', 'confirmed')
            ->groupBy('destinations.id', 'destinations.name', 'destinations.country')
            ->orderByDesc('booking_count')
            ->take(4)
            ->get();
    }
}