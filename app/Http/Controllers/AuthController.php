<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * REGISTER — Creates a new user account
     */
    public function register(Request $request): JsonResponse
    {
        // validate() checks incoming data against rules.
        // If validation fails, Laravel automatically returns a 422 error response.
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users', // unique:users = email must not already exist
            'password' => 'required|string|min:8|confirmed',    // confirmed = must also send password_confirmation
        ]);

        // Create the user — password is automatically hashed by the User model
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']), // NEVER store plain text passwords!
        ]);

        // Create a token for this user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user'    => $user,
            'token'   => $token,
        ], 201); // 201 = Created
    }

    /**
     * LOGIN — Authenticates an existing user
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists AND password matches
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete old tokens (optional but good practice — single device login)
        $user->tokens()->delete();

        // Issue a fresh token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    /**
     * LOGOUT — Invalidates the user's current token
     */
    public function logout(Request $request): JsonResponse
    {
        // currentAccessToken() gets the token used in this request, then deletes it
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}