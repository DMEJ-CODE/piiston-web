<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Administration\SettingsRepositoryInterface;
use App\Services\Administration\PlatformSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    protected $settingsService;

    protected $settingsRepository;

    public function __construct(PlatformSettingsService $settingsService, SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsService = $settingsService;
        $this->settingsRepository = $settingsRepository;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->settingsRepository->allSettings());
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $adminId = Auth::user()->administrator->id ?? 1; // Fallback for seeder/dev
        $this->settingsService->updateConfig($request->key, $request->value, $adminId);

        return response()->json(['message' => 'Setting updated successfully']);
    }
}
