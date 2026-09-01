<?php

namespace App\Repositories\Notifications;

use App\Models\Notifications\UserDevice;
use Illuminate\Support\Collection;

interface DeviceRepositoryInterface
{
    public function registerDevice(int $userId, array $data): UserDevice;

    public function unregisterDevice(string $deviceToken): bool;

    public function getUserDevices(int $userId): Collection;
}
