<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TravelerController extends Controller
{
    /**
     * Display a listing of the travelers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get traveler statistics
        $totalTravelers = Traveler::count();
        $activeTravelers = Traveler::where('status', 'active')->count();
        
        // Calculate new travelers this month
        $newThisMonth = Traveler::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Calculate growth rates
        $lastMonthCount = Traveler::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        $newGrowthRate = $lastMonthCount > 0 
            ? round((($newThisMonth - $lastMonthCount) / $lastMonthCount) * 100, 1)
            : ($newThisMonth > 0 ? 100 : 0);
            
        // Calculate active growth rate
        $lastMonthActive = Traveler::where('status', 'active')
            ->whereMonth('updated_at', now()->subMonth()->month)
            ->whereYear('updated_at', now()->subMonth()->year)
            ->count();
            
        $activeGrowthRate = $lastMonthActive > 0
            ? round((($activeTravelers - $lastMonthActive) / $lastMonthActive) * 100, 1)
            : ($activeTravelers > 0 ? 100 : 0);
            
        // Calculate average bookings per traveler
        $totalBookings = Booking::count();
        $avgBookings = $totalTravelers > 0 ? $totalBookings / $totalTravelers : 0;
        
        // Get paginated travelers with their booking info
        $travelers = Traveler::with(['booking.user'])
            ->latest()
            ->paginate(10);
            
        return view('travelers.index', compact(
            'travelers',
            'totalTravelers',
            'activeTravelers',
            'newThisMonth',
            'newGrowthRate',
            'activeGrowthRate',
            'avgBookings'
        ));
    }

    /**
     * Show the form for creating a new traveler.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bookings = Booking::with('user')->latest()->get();
        return view('travelers.create', compact('bookings'));
    }

    /**
     * Store a newly created traveler in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:travelers,email',
            'phone' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create the traveler
            $traveler = Traveler::create([
                'booking_id' => $validated['booking_id'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'status' => 'active',
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('travelers.index')
                ->with('success', 'Traveler created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating traveler: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create traveler. Please try again.');
        }
    }

    /**
     * Display the specified traveler.
     *
     * @param  \App\Models\Traveler  $traveler
     * @return \Illuminate\Http\Response
     */
    public function show(Traveler $traveler)
    {
        $traveler->load(['booking.user', 'booking.package']);
        return view('travelers.show', compact('traveler'));
    }

    /**
     * Show the form for editing the specified traveler.
     *
     * @param  \App\Models\Traveler  $traveler
     * @return \Illuminate\Http\Response
     */
    public function edit(Traveler $traveler)
    {
        $bookings = Booking::with('user')->latest()->get();
        return view('travelers.edit', compact('traveler', 'bookings'));
    }

    /**
     * Update the specified traveler in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Traveler  $traveler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Traveler $traveler)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:travelers,email,' . $traveler->id,
            'phone' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);
        
        try {
            DB::beginTransaction();
            
            $traveler->update([
                'booking_id' => $validated['booking_id'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'status' => $validated['status'],
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('travelers.index')
                ->with('success', 'Traveler updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating traveler: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update traveler. Please try again.');
        }
    }

    /**
     * Remove the specified traveler from storage.
     *
     * @param  \App\Models\Traveler  $traveler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Traveler $traveler)
    {
        try {
            $traveler->delete();
            
            return redirect()
                ->route('travelers.index')
                ->with('success', 'Traveler deleted successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting traveler: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Failed to delete traveler. Please try again.');
        }
    }
}