<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:12|confirmed',
            ]);

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'message' => 'User registered successfully.',
                'user' => $user,
            ], 200);

        } catch(Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            $user = User::firstWhere('email', $validated['email']);
    
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'token' => $token,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            return response()->json($request->user());
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePreferences(Request $request)
    {
        try {

            $validated = $request->validate([
                'authors' => 'array',
                'authors.*' => 'string|max:255',
                'categories' => 'array',
                'categories.*' => 'string|max:255',
                'sources' => 'array',
                'sources.*' => 'string|max:255',
            ]);

            $user = Auth::user();

            $user->updatePreferences($validated);

            return response()->json([
                'message' => 'User preferences successfully updated.',
            ], 200);
            
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully.']);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}