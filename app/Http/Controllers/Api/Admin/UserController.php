<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'country']);

        if ($request->has('search')) {
            $query->where('email', 'like', "%{$request->search}%")
                ->orWhere('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%");
        }

        return response()->json($query->paginate(30));
    }

    public function suspend(User $user)
    {
        $user->update(['status' => 'suspended']);

        return response()->json(['message' => "User {$user->email} has been suspended."]);
    }

    public function reactivate(User $user)
    {
        $user->update(['status' => 'active']);

        return response()->json(['message' => "User {$user->email} has been reactivated."]);
    }
}
