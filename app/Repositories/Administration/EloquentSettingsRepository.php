<?php

namespace App\Repositories\Administration;

use App\Models\Administration\FeatureFlag;
use App\Models\Administration\SystemSetting;
use Illuminate\Support\Collection;

class EloquentSettingsRepository implements SettingsRepositoryInterface
{
    public function getSetting(string $key): ?SystemSetting
    {
        return SystemSetting::where('setting_key', $key)->first();
    }

    public function updateSetting(string $key, string $value, int $adminId): bool
    {
        return SystemSetting::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value, 'updated_by' => $adminId]
        )->wasRecentlyCreated || true;
    }

    public function allSettings(): Collection
    {
        return SystemSetting::all();
    }

    public function getFeatureFlag(string $name): ?FeatureFlag
    {
        return FeatureFlag::where('feature_name', $name)->first();
    }

    public function updateFeatureFlag(string $name, bool $enabled): bool
    {
        return FeatureFlag::updateOrCreate(
            ['feature_name' => $name],
            ['enabled' => $enabled]
        )->wasRecentlyCreated || true;
    }

    public function allFeatureFlags(): Collection
    {
        return FeatureFlag::all();
    }
}
