<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user'  => $user,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user'  => $user,
        ]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
