<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Identity\ActivityResource;
use App\Models\Identity\UserActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 20);

        $activities = UserActivity::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json(ActivityResource::collection($activities));
    }
}
