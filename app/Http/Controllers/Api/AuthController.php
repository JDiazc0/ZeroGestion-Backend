<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registerUser(RegisterUserRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'theme_id' => $request->theme_id,
        ]);

        return response()->json([
            'message' => 'User registered correctly',
            'user' => new UserResource($user)
        ], Response::HTTP_CREATED);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            /** @var \App\Model\User */
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            $cookie = cookie('auth_token', $token, 60 * 24, '/', null, true, true, false, 'Strict');

            return response()->json([
                'auth_token' => $token
            ], Response::HTTP_OK)->withCookie($cookie);
        } else {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
