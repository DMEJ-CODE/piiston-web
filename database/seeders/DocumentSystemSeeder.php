<?php

namespace Database\Seeders;

use App\Models\Documents\DocumentCategory;
use App\Models\Documents\DocumentTemplate;
use App\Models\Documents\DocumentType;
use Illuminate\Database\Seeder;

class DocumentSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = [
            ['name' => 'Vehicle', 'icon' => 'directions_car'],
            ['name' => 'Finance', 'icon' => 'payments'],
            ['name' => 'Identity', 'icon' => 'badge'],
            ['name' => 'Repair', 'icon' => 'build'],
        ];

        foreach ($categories as $cat) {
            DocumentCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // 2. Types
        $types = [
            ['name' => 'Registration Certificate', 'category_id' => 1, 'expires' => true],
            ['name' => 'Driver License', 'category_id' => 3, 'expires' => true, 'requires_approval' => true],
            ['name' => 'Invoice', 'category_id' => 2, 'requires_signature' => true],
            ['name' => 'Repair Report', 'category_id' => 4],
        ];

        foreach ($types as $type) {
            DocumentType::firstOrCreate(['name' => $type['name']], $type);
        }

        // 3. Templates
        DocumentTemplate::firstOrCreate(['name' => 'standard_invoice'], [
            'category' => 'Finance',
            'template_file' => 'templates/invoice_v1.blade.php',
        ]);
    }
}
