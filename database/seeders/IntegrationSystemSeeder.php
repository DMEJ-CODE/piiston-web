<?php

namespace Database\Seeders;

use App\Models\Integrations\ApiClient;
use App\Models\Integrations\ApiVersion;
use App\Models\Integrations\IntegrationProvider;
use Illuminate\Database\Seeder;

class IntegrationSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Providers
        $providers = [
            ['name' => 'OpenStreetMap', 'category' => 'MAPS', 'documentation_url' => 'https://www.openstreetmap.org'],
            ['name' => 'MTN Mobile Money', 'category' => 'PAYMENT', 'documentation_url' => 'https://momodeveloper.mtn.com'],
            ['name' => 'Orange Money', 'category' => 'PAYMENT', 'documentation_url' => 'https://www.orange.cm'],
            ['name' => 'OpenAI', 'category' => 'AI', 'documentation_url' => 'https://platform.openai.com'],
        ];

        foreach ($providers as $p) {
            IntegrationProvider::firstOrCreate(['name' => $p['name']], $p);
        }

        // 2. Sample Internal API Client
        ApiClient::firstOrCreate(['name' => 'Piiston Mobile App'], [
            'type' => 'INTERNAL',
            'status' => true,
        ]);

        // 3. API Versioning
        ApiVersion::firstOrCreate(['version_name' => 'v1'], [
            'release_date' => '2026-08-01',
            'status' => true,
        ]);
    }
}
