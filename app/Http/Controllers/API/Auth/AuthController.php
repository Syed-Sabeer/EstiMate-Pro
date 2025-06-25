<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid login credentials'], 401);
            }
            $token = $user->createToken($user->name, ['auth_token'])->plainTextToken;
            $refreshToken = Str::random(64);
            $user->update(['refresh_token' => $refreshToken]);

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return response()->json([
                    'error' => 'Email not verified',
                    'user' => $user,
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'refresh_token' => $refreshToken
                ], 403);
            }

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token_type' => 'Bearer',
                'token' => $token,
                'refresh_token' => $refreshToken
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during login'], 500);
        }
    }

    public function register(Request $request): JsonResponse
    {
        $rules = array(
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|max:255',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password),
            ]);

            $profile = Profile::create([
                'user_id' => $user->id,
                'first_name' => $request->name,
            ]);

            // Send email verification notification
            $user->sendEmailVerificationNotification();

            $token = $user->createToken($user->name, ['auth_token'])->plainTextToken;
            DB::commit();
            return response()->json([
                'message' => 'Registration successfull',
                'user' => $user,
                'token_type' => 'Bearer',
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred during registration'], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logout successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during logout'], 500);
        }
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        $user = User::findOrFail($request->route('id'));
        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }
        Mail::to($user->email)->send(new WelcomeMail($user));
        // return response()->json([
        //     'message' => 'Email verified successfully, and welcome email sent.'
        // ], 200);

        return redirect()->away('http://localhost:5173/auth/login');
    }

    public function sendVerificationNotification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent!'], 200);
    }

    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $rules = array(
            'email' => 'required|email|exists:users,email'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    public function resetPasswordToken(string $token)
    {
        return redirect()->away('http://localhost:5173/password/reset?token=' . $token);
        // return response()->json([
        //     'token' => $token,
        //     'message' => 'Reset password token'
        // ], 200);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $rules = array(
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->refresh_token;
        $user = User::where('refresh_token', $refreshToken)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }
        $user->tokens()->delete();
        $accessToken = $user->createToken($user->name, ['auth_token'])->plainTextToken;
        return response()->json([
            'token_type' => 'Bearer',
            'token' => $accessToken,
        ], 200);
    }
}
