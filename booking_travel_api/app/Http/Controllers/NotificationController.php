<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $notifications = Notification::with('user')->paginate(10); // Paginate for performance
        return response()->json(['status' => 'success', 'data' => $notifications], 200);
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
            'is_read' => 'boolean'
        ]);

        $notification = Notification::create($validated);

        return response()->json(['status' => 'success', 'data' => $notification], 201);
    }

    /**
     * Display a specific notification.
     */
    public function show(Notification $notification)
    {
        $notification->load('user');
        return response()->json(['status' => 'success', 'data' => $notification], 200);
    }

    /**
     * Update a specific notification.
     */
    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'message' => 'sometimes|string|max:255',
            'is_read' => 'sometimes|boolean'
        ]);

        $notification->update($validated);

        return response()->json(['status' => 'success', 'data' => $notification], 200);
    }

    /**
     * Remove a specific notification.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json(['status' => 'success', 'message' => 'Notification deleted successfully'], 200);
    }
}
