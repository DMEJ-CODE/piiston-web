<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AI\AiProvider;
use App\Models\AI\AiUsageLog;
use Illuminate\Http\JsonResponse;

class AIController extends Controller
{
    public function providers(): JsonResponse
    {
        return response()->json(AiProvider::with('models')->get());
    }

    public function usage(): JsonResponse
    {
        return response()->json(AiUsageLog::with('user')->orderBy('created_at', 'desc')->paginate(50));
    }
}
