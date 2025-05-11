<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $userData = $request->validated();
        $user = User::create($userData);
        $token = $user->createToken('access_token', ['*'], now()->addHour())->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addDay())->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('access_token', ['*'], now()->addHour())->plainTextToken;
            $refreshToken = Auth::user()->createToken('refresh_token', ['refresh'], now()->addDay())->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'refresh_token' => $refreshToken,
            ]);
        }
        return response()->json([
            'message' => 'Wrong email or password',
        ], 401);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function refreshToken(Request $request)
    {
        if (!$request->user()->currentAccessToken()->can('refresh')) {
            return response()->json([
                'message' => 'Access denied'
            ], 401);
        }
        try {
            $request->user()->tokens()->where('name', 'access_token')->delete();
            $token = $request->user()->createToken('access_token', ['*'], now()->addHour())->plainTextToken;
            return response()->json([
                'access_token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token refresh failed'], 500);
        }
    }
}
