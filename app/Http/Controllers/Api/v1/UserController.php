<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\UpdateLanguageRequest;
use App\Http\Resources\Identity\UserResource;
use App\Services\Identity\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile(Request $request): UserResource
    {
        return new UserResource($request->user()->load('preferredLanguage'));
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $data = $request->all();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            if ($path === false) {
                return response()->json([
                    'message' => 'Avatar could not be stored',
                ], 500);
            }

            $data['profile_photo'] = Storage::url($path);
        }

        $updated = $this->userService->updateProfile($request->user(), $data);

        if (! $updated) {
            return response()->json([
                'message' => 'Profile could not be updated',
            ], 422);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($request->user()->fresh()->load(['roles', 'preferredLanguage'])),
        ]);
    }

    /**
     * Stores the caller's preferred language, keyed by `languages.code`.
     */
    public function updateLanguage(UpdateLanguageRequest $request): JsonResponse
    {
        $updated = $this->userService->updateLanguage(
            $request->user(),
            $request->validated('language'),
        );

        if (! $updated) {
            return response()->json([
                'message' => 'Language could not be updated',
            ], 422);
        }

        return response()->json([
            'message' => 'Language updated successfully',
            'user' => new UserResource($request->user()->fresh()->load(['roles', 'preferredLanguage'])),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        // Cleanup storage
        if ($user->avatar_url) {
            $path = str_replace('/storage/', '', $user->avatar_url);
            Storage::disk('public')->delete($path);
        }

        $this->userService->deleteAccount($user);

        return response()->json(['message' => 'Account permanently deleted']);
    }
}
