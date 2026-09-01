<?php

namespace App\Http\Controllers\Api\v1\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\RegisterDeviceRequest;
use App\Repositories\Notifications\DeviceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    protected $deviceRepository;

    public function __construct(DeviceRepositoryInterface $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    public function register(RegisterDeviceRequest $request): JsonResponse
    {
        $device = $this->deviceRepository->registerDevice(Auth::id(), $request->validated());

        return response()->json(['message' => 'Device registered successfully', 'device' => $device], 201);
    }

    public function unregister(Request $request): JsonResponse
    {
        $request->validate(['device_token' => 'required|string']);
        $this->deviceRepository->unregisterDevice($request->device_token);

        return response()->json(['message' => 'Device unregistered successfully']);
    }
}
