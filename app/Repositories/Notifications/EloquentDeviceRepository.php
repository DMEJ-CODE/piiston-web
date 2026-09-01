<?php

namespace App\Repositories\Notifications;

use App\Models\Notifications\UserDevice;
use Illuminate\Support\Collection;

class EloquentDeviceRepository implements DeviceRepositoryInterface
{
    public function registerDevice(int $userId, array $data): UserDevice
    {
        return UserDevice::updateOrCreate(
            ['device_token' => $data['device_token']],
            $data + ['user_id' => $userId, 'last_active' => now(), 'status' => true]
        );
    }

    public function unregisterDevice(string $deviceToken): bool
    {
        return UserDevice::where('device_token', $deviceToken)->delete();
    }

    public function getUserDevices(int $userId): Collection
    {
        return UserDevice::where('user_id', $userId)->where('status', true)->get();
    }
}
