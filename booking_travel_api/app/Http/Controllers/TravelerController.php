<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TravelerController extends Controller
{
    public function index(Request $request)
    {
        // Base query for travelers with bookings count
        $query = Traveler::withCount(['bookings'])
            ->with(['latestBooking.package']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Get paginated results
        $travelers = $query->withCount('bookings')
            ->with(['latestBooking' => function($q) {
                $q->latest()->first();
            }])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Calculate statistics
        $totalTravelers = Traveler::count();
        
        // Use string literals for enum values
        $activeTravelers = Traveler::where('status', 'active')->count();
        $newThisMonth = Traveler::whereMonth('created_at', now()->month)->count();
        
        // Calculate growth rates (simplified for example)
        $lastMonthCount = Traveler::whereMonth('created_at', now()->subMonth()->month)->count();
        $growthRate = $lastMonthCount > 0 
            ? round((($totalTravelers - $lastMonthCount) / $lastMonthCount) * 100, 1)
            : 100;
            
        $activeLastMonth = Traveler::where('status', 'active')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();
            
        $activeGrowthRate = $activeLastMonth > 0
            ? round((($activeTravelers - $activeLastMonth) / $activeLastMonth) * 100, 1)
            : 100;
            
        $newLastMonth = Traveler::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        $newGrowthRate = $newLastMonth > 0
            ? round((($newThisMonth - $newLastMonth) / $newLastMonth) * 100, 1)
            : 100;
        
        // Calculate average bookings per traveler
        $avgBookings = $totalTravelers > 0 
            ? Traveler::has('bookings')->withCount('bookings')->avg('bookings_count')
            : 0;

        return view('travelers.index', [
            'travelers' => $travelers,
            'totalTravelers' => $totalTravelers,
            'activeTravelers' => $activeTravelers,
            'newThisMonth' => $newThisMonth,
            'growthRate' => $growthRate,
            'activeGrowthRate' => $activeGrowthRate,
            'newGrowthRate' => $newGrowthRate,
            'avgBookings' => $avgBookings,
        ]);
    }

    public function show(Traveler $traveler)
    {
        $traveler->load(['bookings.package', 'messages']);
        return view('travelers.show', compact('traveler'));
    }

    public function create()
    {
        return view('travelers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:travelers,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'passport_number' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $traveler = Traveler::create($validator->validated());

        return redirect()->route('travelers.show', $traveler)
            ->with('success', 'Traveler created successfully');
    }

    public function edit(Traveler $traveler)
    {
        return view('travelers.edit', compact('traveler'));
    }

    public function update(Request $request, Traveler $traveler)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:travelers,email,' . $traveler->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'passport_number' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $traveler->update($validator->validated());

        return redirect()->route('travelers.show', $traveler)
            ->with('success', 'Traveler updated successfully');
    }

    public function destroy(Traveler $traveler)
    {
        $traveler->delete();
        
        return redirect()->route('travelers.index')
            ->with('success', 'Traveler deleted successfully');
    }
}
