<?php

namespace App\Repositories\Administration;

use App\Models\Administration\FeatureFlag;
use App\Models\Administration\SystemSetting;
use Illuminate\Support\Collection;

interface SettingsRepositoryInterface
{
    public function getSetting(string $key): ?SystemSetting;

    public function updateSetting(string $key, string $value, int $adminId): bool;

    public function allSettings(): Collection;

    public function getFeatureFlag(string $name): ?FeatureFlag;

    public function updateFeatureFlag(string $name, bool $enabled): bool;

    public function allFeatureFlags(): Collection;
}
