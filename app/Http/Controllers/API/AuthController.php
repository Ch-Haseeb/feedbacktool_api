<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create(array_merge($request->validated(), [
            'password' => Hash::make($request->input('password')),
        ]));

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'User created successfully!!'
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'User successfully login!!'
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json(['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

    public function user_feedback(Request $request)
    {
        $user = $request->user();
        $feedbackItems = Feedback::where('user_id', $user->id)->paginate(10);
        return response()->json(['feedback_items' => $feedbackItems]);
    }
}
