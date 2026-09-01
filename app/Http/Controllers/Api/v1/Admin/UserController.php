<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Http\Resources\Admin\AdminUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return AdminUserResource::collection(User::with('roles')->paginate(20));
    }

    public function updateStatus(UpdateUserStatusRequest $request, User $user): JsonResponse
    {
        $user->update(['status' => $request->status]);

        return response()->json([
            'message' => "User status updated to {$request->status}",
            'user' => new AdminUserResource($user),
        ]);
    }

    public function show(User $user): AdminUserResource
    {
        return new AdminUserResource($user->load('roles', 'addresses'));
    }
}
