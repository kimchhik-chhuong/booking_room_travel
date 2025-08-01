<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,employer,user', ['only' => ['index', 'show']]);
        $this->middleware('role:admin,employer', ['only' => ['create', 'store']]);
        $this->middleware('role:admin,employer', ['only' => ['edit', 'update']]);
        $this->middleware('role:admin', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        return view('users.index', [
            'users'       => User::latest()->paginate(6),
            'totalUsers'  => User::count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'adminUsers'  => User::admins()->count(),
            'newUsers'    => User::where('created_at', '>=', now()->subDays(30))->count(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => ['admin', 'employer', 'user'],
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'password'            => 'required|string|min:8|confirmed',
            'role'                => 'required|string|in:admin,employer,user',
            'profile_picture_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'           => 'boolean',
        ]);

        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Handle profile image upload
        if ($request->hasFile('profile_picture_url')) {
            $validatedData['profile_picture_url'] = $request
                ->file('profile_picture_url')
                ->store('users', 'public');
        }

        User::create($validatedData);

        return redirect()
            ->route('users.index')
            ->withSuccess('New user added successfully.');
    }

    /**
     * Display a specific user.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        if ($user->hasRole('Super Admin') && $user->id != auth()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return view('users.edit', compact('user'));
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user): RedirectResponse  
    {
        $validatedData = $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $user->id,
            'password'            => 'nullable|string|min:8|confirmed',
            'role'                => 'required|string|in:admin,employer,user',
            'profile_picture_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'           => 'boolean',
        ]);

        // Update password if provided
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        // Handle image upload and delete old image
        if ($request->hasFile('profile_picture_url')) {
            if ($user->profile_picture_url && Storage::disk('public')->exists($user->profile_picture_url)) {
                Storage::disk('public')->delete($user->profile_picture_url);
            }
            $validatedData['profile_picture_url'] = $request
                ->file('profile_picture_url')
                ->store('users', 'public');
        }

        $user->update($validatedData);

        return redirect()
            ->route('users.index')
            ->withSuccess('User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $authUser = Auth::user();

        // Prevent deletion of Super Admin or self-deletion
        if ($user->role === 'Super Admin' || ($authUser && $user->id == $authUser->id)) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }

        // Delete profile image if exists
        if ($user->profile_picture_url && Storage::disk('public')->exists($user->profile_picture_url)) {
            Storage::disk('public')->delete($user->profile_picture_url);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->withSuccess('User deleted successfully.');
    }
}