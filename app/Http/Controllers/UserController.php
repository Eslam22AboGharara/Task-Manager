<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $account = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        Mail::to($account->email)->send(new WelcomeEmail($account));
        return response()->json([
            'message' => 'Account Created Successfully'
        ], 201);
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'account not found'
            ], 401);
        } else {
            $account = User::where('email', $request->email)->first();
            $Token = $account->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'welcome to ' . $account->name,
                'token' => $Token
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 204);
    }

    public function getuser()
    {
        $user_id = Auth::user()->id;
        $user = User::with('profile')->findOrFail($user_id);
        return new UserResource($user);
    }

    public function all_users()
    {
        $all_users = User::with('profile')->get();
        return UserResource::collection($all_users);
    }
}
