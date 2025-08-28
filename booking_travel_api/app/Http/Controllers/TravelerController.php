<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Traveler;
use Illuminate\Support\Facades\Validator;

class TravelerController extends Controller
{
    /**
     * Display a listing of the travelers.
     */
    public function index()
    {
        $travelers = Traveler::all();
        return response()->json($travelers);
    }

    /**
     * Show the form for creating a new traveler.
     */
    public function create()
    {
        return view('travelers.create');
    }

    /**
     * Store a newly created traveler in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:travelers,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $traveler = Traveler::create($request->all());
        return response()->json($traveler, 201);
    }

    /**
     * Display the specified traveler.
     */
    public function show($id)
    {
        $traveler = Traveler::findOrFail($id);
        return response()->json($traveler);
    }

    /**
     * Show the form for editing the specified traveler.
     */
    public function edit($id)
    {
        $traveler = Traveler::findOrFail($id);
        return view('travelers.edit', compact('traveler'));
    }

    /**
     * Update the specified traveler in storage.
     */
    public function update(Request $request, $id)
    {
        $traveler = Traveler::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:travelers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $traveler->update($request->all());
        return response()->json($traveler);
    }

    /**
     * Remove the specified traveler from storage.
     */
    public function destroy($id)
    {
        $traveler = Traveler::findOrFail($id);
        $traveler->delete();
        return response()->json(null, 204);
    }
}