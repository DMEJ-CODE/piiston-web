<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Identity\UserResource;
use App\Models\User;
use App\Services\Identity\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        \Log::info('Registering process started', $request->all());
        try {
            $result = $this->authService->register($request->validated());

            return response()->json([
                'message' => 'User registered successfully',
                'user' => new UserResource($result['user']),
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
            ], 201);
        } catch (ValidationException $e) {
            \Log::error('Validation failed during register', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Unexpected error during register', ['msg' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($result['user']),
            'access_token' => $result['access_token'],
            'token_type' => $result['token_type'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request): UserResource
    {
        return new UserResource($request->user()->load('roles'));
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $message = $this->authService->forgotPassword($request->email);

        return response()->json(['message' => $message]);
    }

    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $exists = User::where('email', $request->email)->exists();

        if ($exists) {
            return response()->json(['message' => 'Email already taken'], 422);
        }

        return response()->json(['message' => 'Email available']);
    }

    public function checkPhone(Request $request): JsonResponse
    {
        $request->validate(['phone' => 'required|string|max:20']);
        $exists = User::where('phone', $request->phone)->exists();

        if ($exists) {
            return response()->json(['message' => 'Phone number already registered'], 422);
        }

        return response()->json(['message' => 'Phone number available']);
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate(['phone' => 'required|string|max:20']);

        if (User::where('phone', $request->phone)->exists()) {
            return response()->json(['message' => 'This phone number is already registered.'], 422);
        }

        $message = $this->authService->sendOtp($request->phone);

        return response()->json(['message' => $message]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'phone' => 'required|string|max:20',
        ]);

        $verified = $this->authService->verifyOtp($request->user(), $request->code, $request->phone);

        if ($verified) {
            return response()->json(['message' => 'Phone number verified successfully']);
        }

        return response()->json(['message' => 'Invalid verification code'], 422);
    }
}
