<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Identity\UserResource;
use App\Services\Identity\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $data = $request->all();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['profile_photo'] = Storage::url($path);
        }

        $updated = $this->userService->updateProfile($request->user(), $data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($request->user()->fresh()),
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
