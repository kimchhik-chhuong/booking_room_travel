<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $conversations = Message::select('user_id', 'subject', 'content', 'created_at', 'is_read', 'priority')
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->groupBy('user_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->name = $item->user->name ?? 'Unknown';
                $item->last_message = $item->content;
                $item->updated_at = $item->created_at->format('h:i A'); // Format time (e.g., 07:52 PM)
                return $item;
            });

        return response()->json(['success' => true, 'data' => $conversations], 200);
    }

    /**
     * Store a newly created resource in storage (send message).
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $targetUser = \App\Models\User::find($validated['user_id']);
        if (!$targetUser || !$targetUser->is_active) {
            return response()->json(['success' => false, 'message' => 'Cannot message an inactive user'], 403);
        }

        $message = Message::create([
            'user_id' => $validated['user_id'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'is_read' => false,
            'priority' => $validated['priority'],
            'created_at' => now()->setTimezone('Asia/Bangkok'), // Updated to 07:52 PM +07
            'updated_at' => now()->setTimezone('Asia/Bangkok'),
        ]);

        return response()->json(['success' => true, 'message' => 'Message sent', 'data' => $message], 201);
    }

    /**
     * Display the specified resource (messages for a user).
     */
    public function show(string $id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $targetUser = \App\Models\User::find($id);
        if (!$targetUser || !$targetUser->is_active) {
            return response()->json(['success' => false, 'message' => 'User not found or inactive'], 404);
        }

        $messages = Message::where('user_id', $id)->get()->map(function ($item) {
            $item->sender = $item->user_id == Auth::id() ? 'You' : ($item->user->name ?? 'Unknown');
            $item->time = $item->created_at->format('h:i A'); // Format time
            return $item;
        });

        return response()->json(['success' => true, 'data' => $messages], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'is_read' => 'boolean',
            'priority' => 'in:low,medium,high',
        ]);

        $message = Message::find($id);
        if (!$message) {
            return response()->json(['success' => false, 'message' => 'Message not found'], 404);
        }

        $message->update($validated);

        return response()->json(['success' => true, 'message' => 'Message updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $message = Message::find($id);
        if (!$message) {
            return response()->json(['success' => false, 'message' => 'Message not found'], 404);
        }

        $message->delete();

        return response()->json(['success' => true, 'message' => 'Message deleted'], 200);
    }
}