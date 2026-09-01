<?php

namespace App\Services\Administration;

use App\Repositories\Administration\SettingsRepositoryInterface;

class PlatformSettingsService
{
    protected $settingsRepository;

    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function toggleFeature(string $name, bool $enabled): void
    {
        $this->settingsRepository->updateFeatureFlag($name, $enabled);
    }

    public function updateConfig(string $key, string $value, int $adminId): void
    {
        $this->settingsRepository->updateSetting($key, $value, $adminId);
    }
}
