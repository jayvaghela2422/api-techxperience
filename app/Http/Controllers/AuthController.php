<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Login API
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // create token
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token'   => $token,
                'user'    => $user,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during login',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Logout (invalidate current token)
    public function logout(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = $request->user()    ;

            // delete current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during logout',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}