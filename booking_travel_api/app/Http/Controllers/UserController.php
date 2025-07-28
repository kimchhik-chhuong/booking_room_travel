<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,employee,user', ['only' => ['index','show']]);
        $this->middleware('role:admin,employee', ['only' => ['create','store']]);
        $this->middleware('role:admin,employee', ['only' => ['edit','update']]);
        $this->middleware('role:admin', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('users.index', [
        'users' => User::latest()->paginate(6),
        'totalUsers' => User::count(),
        'activeUsers' => User::where('is_active', true)->count(),
        'adminUsers' => User::where('role', 'admin')->count(),
        'newUsers' => User::where('created_at', '>=', now()->subDays(30))->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => ['admin', 'employee', 'user']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,employee,user',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        if ($request->hasFile('images')) {
            $validatedData['images'] = $request->file('images')->store('users', 'public');
        }

        $user = User::create($validatedData);
        $user->role = $validatedData['role'];
        $user->save();

        return redirect()->route('users.index')
                ->withSuccess('New user added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
{
    return response()->json($user);
}

<<<<<<< HEAD
=======
    public function show(User $user): View
    {
        return view('users.show', [
            'user' => $user
        ]);
    }
>>>>>>> main
=======
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
{
    if ($user->hasRole('Super Admin') && $user->id != auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
<<<<<<< HEAD
=======
    public function edit(User $user): View
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => ['admin', 'employee', 'user'],
            'userRole' => $user->role
        ]);
>>>>>>> main
=======
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b
    }

    return response()->json([
        'user' => $user,
        'roles' => ['admin', 'employee', 'user'],
        'userRole' => $user->role,
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,employee,user',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        // Handle image upload
        if ($request->hasFile('images')) {
            // Delete old image if exists
            if ($user->images && Storage::disk('public')->exists($user->images)) {
                Storage::disk('public')->delete($user->images);
            }
            $validatedData['images'] = $request->file('images')->store('users', 'public');
        }

        $user->role = $validatedData['role'];
        $user->update($validatedData);

        return redirect()->route('users.index')
                ->withSuccess('User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // About if user is Super Admin or user is deleting himself
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id)
        {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }

        // Delete user image if exists
        if ($user->images) {
            Storage::disk('public')->delete($user->images);
        }

        $user->syncRoles([]);
        $user->delete();

        return redirect()->route('users.index')
                ->withSuccess('User deleted successfully.');
    }
}
