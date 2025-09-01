<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::latest()->paginate(10);
        return view('deals.index', compact('deals'));
    }

    public function create()
    {
        $formData = [
            'title' => ['label' => 'Deal Name', 'type' => 'text', 'placeholder' => 'Enter deal name', 'required' => true],
            'discount' => ['label' => 'Discount', 'type' => 'text', 'placeholder' => 'e.g., 50% Off or $200 Off', 'required' => true],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'placeholder' => 'Enter deal description', 'required' => true],
            'code' => ['label' => 'Promo Code', 'type' => 'text', 'placeholder' => 'e.g., KHMER2025', 'required' => true],
            'valid_until' => ['label' => 'Valid Until', 'type' => 'date', 'required' => true],
            'limit' => ['label' => 'Usage Limit', 'type' => 'number', 'placeholder' => 'e.g., 400', 'required' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['Active' => 'Active', 'Scheduled' => 'Scheduled', 'Expired' => 'Expired'], 'required' => true],
            'color' => ['label' => 'Gradient Color', 'type' => 'select', 'options' => [
                'from-orange-400 to-pink-500' => 'Orange to Pink', 
                'from-blue-400 to-purple-500' => 'Blue to Purple', 
                'from-emerald-400 to-blue-500' => 'Emerald to Blue'
            ], 'required' => true],
        ];
        
        return view('deals.form', [
            'formData' => $formData,
            'action' => route('deals.store'),
            'method' => 'POST',
            'title' => 'Create New Deal'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'discount' => 'required|string|max:50',
            'description' => 'required|string',
            'code' => 'required|string|max:50|unique:deals',
            'valid_until' => 'required|date|after:today',
            'limit' => 'required|integer|min:1',
            'status' => 'required|in:Active,Scheduled,Expired',
            'color' => 'required|string|max:255',
        ]);

        $deal = Deal::create($validated);

        return redirect()->route('deals.index')
            ->with('success', 'Deal created successfully.');
    }

    public function edit(Deal $deal)
    {
        $formData = [
            'title' => ['label' => 'Deal Name', 'type' => 'text', 'placeholder' => 'Enter deal name', 'required' => true],
            'discount' => ['label' => 'Discount', 'type' => 'text', 'placeholder' => 'e.g., 50% Off or $200 Off', 'required' => true],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'placeholder' => 'Enter deal description', 'required' => true],
            'code' => ['label' => 'Promo Code', 'type' => 'text', 'placeholder' => 'e.g., KHMER2025', 'required' => true],
            'valid_until' => ['label' => 'Valid Until', 'type' => 'date', 'required' => true],
            'limit' => ['label' => 'Usage Limit', 'type' => 'number', 'placeholder' => 'e.g., 400', 'required' => true],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['Active' => 'Active', 'Scheduled' => 'Scheduled', 'Expired' => 'Expired'], 'required' => true],
            'color' => ['label' => 'Gradient Color', 'type' => 'select', 'options' => [
                'from-orange-400 to-pink-500' => 'Orange to Pink', 
                'from-blue-400 to-purple-500' => 'Blue to Purple', 
                'from-emerald-400 to-blue-500' => 'Emerald to Blue'
            ], 'required' => true],
        ];
        
        return view('deals.form', [
            'deal' => $deal,
            'formData' => $formData,
            'action' => route('deals.update', $deal->id),
            'method' => 'PUT',
            'title' => 'Edit Deal'
        ]);
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'discount' => 'required|string|max:50',
            'description' => 'required|string',
            'code' => 'required|string|max:50|unique:deals,code,' . $deal->id,
            'valid_until' => 'required|date|after:today',
            'limit' => 'required|integer|min:1',
            'status' => 'required|in:Active,Scheduled,Expired',
            'color' => 'required|string|max:255',
        ]);

        $deal->update($validated);

        return redirect()->route('deals.index')
            ->with('success', 'Deal updated successfully');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('deals.index')
            ->with('success', 'Deal deleted successfully');
    }
}