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

    public function logoutUser()
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $user->tokens()->delete();

        $cookie = Cookie::forget('auth_token');

        return response([
            'message' => 'Logout OK'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'message' => 'Password reset link sent successfully.'
            ], Response::HTTP_OK)
            : response()->json([
                'message' => 'Password reset link could not be sent.'
            ], Response::HTTP_BAD_REQUEST);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json([
                'message' => 'Password successfully reset.'
            ], Response::HTTP_OK)
            : response()->json([
                'message' => 'Password could not be reset.'
            ], Response::HTTP_BAD_REQUEST);
    }
}
