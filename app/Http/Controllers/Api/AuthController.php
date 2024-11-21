<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $accessToken = $user->createToken('access_token')->plainTextToken;

        $refreshToken = $this->generateRefreshToken($user);

        return response()->json([
            'access_token' => $accessToken,
            'expires_in' => config('sanctum.expiration', 60) * 60, // Default to 1 hour
            'refresh_token' => $refreshToken,
            'refresh_expires_in' => 7 * 24 * 60 * 60, // Default to 7 days
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $accessToken = $user->createToken('access_token')->plainTextToken;
        $refreshToken = $this->generateRefreshToken($user);

        return response()->json([
            'access_token' => $accessToken,
            'expires_in' => config('sanctum.expiration', 60) * 60, // Default to 1 hour
            'refresh_token' => $refreshToken,
            'refresh_expires_in' => 7 * 24 * 60 * 60, // Default to 7 days
            'token_type' => 'Bearer',
        ]);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $user = $this->validateRefreshToken($request->refresh_token);

        if (!$user) {
            return response()->json(
                ['message' => 'Invalid or expired refresh token'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $accessToken = $user->createToken('access_token')->plainTextToken;
        $newRefreshToken = $this->generateRefreshToken($user);

        return response()->json([
            'access_token' => $accessToken,
            'expires_in' => config('sanctum.expiration', 60) * 60, // Default to 1 hour
            'refresh_token' => $newRefreshToken,
            'refresh_expires_in' => 7 * 24 * 60 * 60, // Default to 7 days
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        // Return empty response with HTTP 204
        return response()->noContent();
    }

    public function me(Request $request)
    {
        $userData = $request->user()->toArray(); // Convert user data to array
        $userData['type'] = 'user'; // Add 'type' field

        return response()->json($userData);
    }

    private function generateRefreshToken(User $user)
    {
        $refreshToken = bin2hex(random_bytes(40)); // Generate a random token
        $user->forceFill([
            'refresh_token' => hash('sha256', $refreshToken),
            'refresh_token_expires_at' => now()->addDays(7), // Default 7 days
        ])->save();

        return $refreshToken;
    }

    private function validateRefreshToken(string $refreshToken)
    {
        $hashedToken = hash('sha256', $refreshToken);

        return User::where('refresh_token', $hashedToken)
            ->where('refresh_token_expires_at', '>', now())
            ->first();
    }
}
