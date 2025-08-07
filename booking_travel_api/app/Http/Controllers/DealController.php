<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::all();
        return response()->json(['success' => true, 'data' => $deals], 200);
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
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['Active', 'Scheduled', 'Expired'], 'required' => true],
            'color' => ['label' => 'Gradient Color', 'type' => 'select', 'options' => ['from-orange-400 to-pink-500', 'from-blue-400 to-purple-500', 'from-emerald-400 to-blue-500'], 'required' => true],
        ];
        return response()->json(['success' => true, 'form' => $formData], 200);
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

        return response()->json(['success' => true, 'message' => 'Deal created', 'data' => $deal], 201);
    }

    public function show($id)
    {
        $deal = Deal::find($id);
        if ($deal) {
            return response()->json(['success' => true, 'data' => $deal], 200);
        }
        return response()->json(['success' => false, 'message' => 'Deal not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $deal = Deal::find($id);
        if (!$deal) {
            return response()->json(['success' => false, 'message' => 'Deal not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'discount' => 'required|string|max:50',
            'description' => 'required|string',
            'code' => 'required|string|max:50|unique:deals,code,' . $id,
            'valid_until' => 'required|date|after:today',
            'limit' => 'required|integer|min:1',
            'status' => 'required|in:Active,Scheduled,Expired',
            'color' => 'required|string|max:255',
        ]);

        $deal->update($validated);

        return response()->json(['success' => true, 'message' => 'Deal updated', 'data' => $deal], 200);
    }

    public function destroy($id)
    {
        $deal = Deal::find($id);
        if ($deal) {
            $deal->delete();
            return response()->json(['success' => true, 'message' => 'Deal deleted'], 200);
        }
        return response()->json(['success' => false, 'message' => 'Deal not found'], 404);
    }
}