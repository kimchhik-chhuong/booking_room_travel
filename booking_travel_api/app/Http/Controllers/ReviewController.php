<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['destination', 'user']);

        // Optional filter by destination
        if ($request->has('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        // Optional filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Paginate results
        $reviews = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $reviews,
        ], 200);
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Review created successfully',
            'data' => $review,
        ], 201);
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['destination', 'user']);

        return response()->json([
            'status' => 'success',
            'data' => $review,
        ], 200);
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|max:1000',
        ]);

        $review->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Review updated successfully',
            'data' => $review,
        ], 200);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully',
        ], 200);
    }
}
