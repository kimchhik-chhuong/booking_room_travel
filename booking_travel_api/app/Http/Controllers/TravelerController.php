<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelerController extends Controller
{
    // Show all travelers with search & filter
    public function index(Request $request)
    {
        $query = Traveler::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $travelers = $query->with('bookings')->paginate(15);

        return view('travelers.index', compact('travelers'));
    }

    // Show single traveler
    public function show(Traveler $traveler)
    {
        // Load related data
        $traveler->load(['bookings.package', 'messages']); 
        // Remove feedback since relation is commented out in model

        return view('travelers.show', compact('traveler'));
    }

    // Show create form
    public function create()
    {
        return view('travelers.create');
    }

    // Store new traveler
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:travelers,email',
            'phone'           => 'nullable|string|max:20',
            'date_of_birth'   => 'nullable|date',
            'passport_number' => 'nullable|string|max:50',
            'nationality'     => 'nullable|string|max:100',
            'address'         => 'nullable|string',
            'status'          => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Traveler::create($validator->validated());

        return redirect()->route('travelers.index')
            ->with('success', 'Traveler created successfully.');
    }

    // Show edit form
    public function edit(Traveler $traveler)
    {
        return view('travelers.edit', compact('traveler'));
    }

    // Update traveler
    public function update(Request $request, Traveler $traveler)
    {
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:travelers,email,' . $traveler->id,
            'phone'           => 'nullable|string|max:20',
            'date_of_birth'   => 'nullable|date',
            'passport_number' => 'nullable|string|max:50',
            'nationality'     => 'nullable|string|max:100',
            'address'         => 'nullable|string',
            'status'          => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $traveler->update($validator->validated());

        return redirect()->route('travelers.index')
            ->with('success', 'Traveler updated successfully.');
    }

    // Delete traveler
    public function destroy(Traveler $traveler)
    {
        $traveler->delete();

        return redirect()->route('travelers.index')
            ->with('success', 'Traveler deleted successfully.');
    }
}
