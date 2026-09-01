<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Providers
        DB::table('ai_providers')->updateOrInsert(
            ['provider_code' => 'GEMINI'],
            [
                'name' => 'Google AI (Gemini)',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $geminiId = DB::table('ai_providers')->where('provider_code', 'GEMINI')->value('id');

        // Models
        DB::table('ai_models')->updateOrInsert(
            ['model_name' => 'gemini-1.5-flash', 'provider_id' => $geminiId],
            [
                'purpose' => 'GENERAL',
                'supports_images' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $flashId = DB::table('ai_models')->where('model_name', 'gemini-1.5-flash')->value('id');

        DB::table('ai_models')->updateOrInsert(
            ['model_name' => 'gemini-1.5-pro', 'provider_id' => $geminiId],
            [
                'purpose' => 'GENERAL',
                'supports_images' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Assistants
        DB::table('ai_assistants')->updateOrInsert(
            ['name' => 'Piiston Expert'],
            [
                'assistant_type' => 'GENERAL',
                'description' => "Tu es l'expert en chef de Piiston, une autorité mondiale en mécanique automobile avec 30 ans d'expérience. Ton ton est professionnel, précis, rassurant et technique mais accessible. Tu ne fais pas de suppositions vagues, tu te bases sur les symptômes pour donner des conseils de niveau ingénieur.",
                'default_model_id' => $flashId,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Prompt Templates
        DB::table('ai_prompt_templates')->updateOrInsert(
            ['name' => 'auto_diagnosis_v1'],
            [
                'purpose' => 'DIAGNOSIS',
                'system_prompt' => "Tu es l'expert en chef de Piiston, un ingénieur en mécanique automobile de haut niveau. Ton rôle est de diagnostiquer avec précision les pannes. Analyse les symptômes suivants pour un véhicule {vehicle_brand} {vehicle_model} de l'année {vehicle_year} avec {mileage} km.\n\nSymptômes: {symptoms}\n\nFournis une analyse rigoureuse. Réponds UNIQUEMENT au format JSON avec les clés suivantes:\n- causes: liste de chaînes (sois technique, ex: 'Usure prématurée du joint de culasse', 'Défaillance du capteur PMH')\n- actions: liste de chaînes (ex: 'Mesurer la compression des cylindres', 'Vérifier la tension aux bornes de l\'alternateur')\n- urgency: 'LOW', 'MEDIUM', 'HIGH'\n- confidence: nombre entre 0 et 100",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
