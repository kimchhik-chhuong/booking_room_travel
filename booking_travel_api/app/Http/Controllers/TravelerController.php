<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use Illuminate\Http\Request;

class TravelerController extends Controller
{
    public function index()
    {
        $travelers = Traveler::orderBy('created_at', 'desc')->get();
        return view('travelers.index', compact('travelers'));
    }

    public function create()
    {
        return view('travelers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:travelers,email',
            'phone_number' => 'nullable|string|max:20',
        ]);

        Traveler::create($request->all());

        return redirect()->route('travelers.index')->with('success', 'Traveler added successfully.');
    }

    public function destroy($id)
    {
        $traveler = Traveler::findOrFail($id);
        $traveler->delete();
        return redirect()->route('travelers.index')->with('success', 'Traveler deleted successfully.');
    }
}
