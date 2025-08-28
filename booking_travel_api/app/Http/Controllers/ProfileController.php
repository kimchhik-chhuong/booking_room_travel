<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Message;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get user's booking statistics
        $totalBookings = Booking::where('traveler_id', $user->id)->count();
        $completedBookings = Booking::where('traveler_id', $user->id)
                                  ->where('status', 'completed')
                                  ->count();
        $totalSpent = Booking::where('traveler_id', $user->id)
                            ->where('payment_status', 'paid')
                            ->sum('total_amount');
        
        // Get recent bookings
        $recentBookings = Booking::with('package')
                                ->where('traveler_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
        
        // Get unread messages
        $unreadMessages = Message::where('traveler_id', $user->id)
                                ->where('is_read', false)
                                ->count();
        
        return view('profile.show', compact(
            'user',
            'totalBookings',
            'completedBookings', 
            'totalSpent',
            'recentBookings',
            'unreadMessages'
        ));
    }
    
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:travelers,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'nationality' => 'nullable|string|max:100',
            'passport_number' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);
        
        // Update basic information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'nationality' => $request->nationality,
            'passport_number' => $request->passport_number,
            'date_of_birth' => $request->date_of_birth,
        ]);
        
        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Upload user avatar.
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
            Storage::delete('public/avatars/' . $user->avatar);
        }
        
        // Store new avatar
        $avatarName = time() . '.' . $request->avatar->extension();
        $request->avatar->storeAs('public/avatars', $avatarName);
        
        // Update user avatar
        $user->update(['avatar' => $avatarName]);
        
        return response()->json([
            'success' => true,
            'avatar_url' => Storage::url('avatars/' . $avatarName)
        ]);
    }
}
