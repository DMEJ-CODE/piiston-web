<?php

namespace Database\Seeders;

use App\Models\AI\AiPromptTemplate;
use App\Models\BI\KPI;
use App\Models\Documents\DocumentCategory;
use App\Models\Documents\DocumentType;
use Illuminate\Database\Seeder;

class EnterpriseUpgradeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AI Prompts
        AiPromptTemplate::updateOrCreate(['name' => 'auto_diagnosis_v1'], [
            'purpose' => 'DIAGNOSIS',
            'system_prompt' => 'Analyze vehicle symptoms: {symptoms}. Brand: {vehicle_brand}.',
            'variables' => ['symptoms', 'vehicle_brand'],
            'status' => true,
        ]);

        // 2. Business Metrics
        KPI::updateOrCreate(['code' => 'monthly_revenue'], [
            'name' => 'Monthly Revenue',
            'category' => 'FINANCE',
            'unit' => 'XAF',
            'status' => true,
        ]);

        // 3. DMS Structure
        $cat = DocumentCategory::firstOrCreate(['name' => 'Compliance']);
        DocumentType::firstOrCreate(['name' => 'Tax Certificate'], [
            'category_id' => $cat->id,
            'requires_approval' => true,
        ]);
    }
}
