<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Log;
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b
use App\Models\User;

class AuthController extends Controller
{
    /**
<<<<<<< HEAD
     * Handle user registration
=======
     * Handle user registration.
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|in:user,admin,employee',
        ]);

<<<<<<< HEAD
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json([
                'message' => 'User already exists. Please login instead.',
                'user' => $existingUser,
            ], 409);
=======
        try {
            $user = User::create([
                'name'     => $validatedData['name'],
                'email'    => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role'     => $validatedData['role'],
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message'      => 'Registration successful',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Registration failed. Please try again later.',
            ], 500);
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    /**
<<<<<<< HEAD
     * Handle user login
=======
     * Handle user login (web + API).
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Invalid credentials'], 401)
                : back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

<<<<<<< HEAD
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
=======
        /** @var User $user */
        $user = Auth::user();

        // Allow all roles to login for API requests
        if ($request->wantsJson()) {
            // No role restriction for API login (Flutter app)
            // Just proceed
        } else {
            // For web login, allow only admin and employee roles
            if (!in_array($user->role, ['admin', 'employee'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'Unauthorized role for login'])->withInput();
            }
        }

        // Update last login time
        $user->last_login = now();
        $user->save();

        // Return API response
        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message'      => 'Login successful',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user,
            ], 200);
        }
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b

        // Return web redirect
        return redirect()->route('dashboard');
    }

    /**
<<<<<<< HEAD
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
=======
     * Handle user logout (web + API).
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            // For API tokens
            if ($request->user() && $request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            }

            // Logout from web session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
>>>>>>> be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b

        return $request->wantsJson()
            ? response()->json(['message' => 'Logged out successfully'], 200)
            : redirect()->route('login');
    }
}
