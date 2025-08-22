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
        // Fetch all conversations with the latest message for each user
        $conversations = Message::select('user_id', 'subject', 'content', 'created_at', 'is_read', 'priority')
            ->groupBy('user_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->name = $item->user->name ?? 'Unknown'; // Assuming User model has a 'name' field
                $item->last_message = $item->content;
                $item->updated_at = $item->created_at;
                return $item;
            });

        return response()->json(['success' => true, 'data' => $conversations], 200);
    }

    /**
     * Store a newly created resource in storage (send message).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $message = Message::create([
            'user_id' => $validated['user_id'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'is_read' => false,
            'priority' => $validated['priority'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Message sent'], 201);
    }

    /**
     * Display the specified resource (messages for a user).
     */
    public function show(string $id)
    {
        $messages = Message::where('user_id', $id)->get()->map(function ($item) {
            $item->sender = $item->user_id == Auth::id() ? 'You' : $item->user->name ?? 'Unknown';
            return $item;
        });

        return response()->json(['success' => true, 'data' => $messages], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}