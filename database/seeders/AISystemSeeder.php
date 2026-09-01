<?php

namespace Database\Seeders;

use App\Models\AI\AiAssistant;
use App\Models\AI\AiModel;
use App\Models\AI\AiPromptTemplate;
use App\Models\AI\AiProvider;
use Illuminate\Database\Seeder;

class AISystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AI Providers
        $openai = AiProvider::firstOrCreate(['provider_code' => 'OPENAI'], [
            'name' => 'OpenAI',
            'api_endpoint' => 'https://api.openai.com/v1',
            'authentication_type' => 'bearer',
        ]);

        $google = AiProvider::firstOrCreate(['provider_code' => 'GOOGLE'], [
            'name' => 'Google Gemini',
            'api_endpoint' => 'https://generativelanguage.googleapis.com',
            'authentication_type' => 'api_key',
        ]);

        // 2. AI Models
        $gpt4o = AiModel::firstOrCreate(['model_name' => 'gpt-4o', 'provider_id' => $openai->id], [
            'purpose' => 'chat',
            'max_tokens' => 128000,
            'supports_images' => true,
        ]);

        $geminiPro = AiModel::firstOrCreate(['model_name' => 'gemini-1.5-pro', 'provider_id' => $google->id], [
            'purpose' => 'chat',
            'max_tokens' => 1000000,
            'supports_images' => true,
            'supports_documents' => true,
        ]);

        // 3. AI Assistants
        AiAssistant::firstOrCreate(['assistant_type' => 'VEHICLE_DIAGNOSIS'], [
            'name' => 'Piiston Master Mechanic',
            'description' => 'Spécialisé en diagnostic automobile et dépannage technique.',
            'default_model_id' => $gpt4o->id,
            'language_support' => ['fr', 'en'],
            'status' => true,
        ]);

        AiAssistant::firstOrCreate(['assistant_type' => 'MECHANIC_ASSISTANT'], [
            'name' => 'Support Technique Pro',
            'description' => 'Assistant expert pour mécaniciens. Aide aux procédures de réparation et interprétation DTC.',
            'default_model_id' => $gpt4o->id,
            'language_support' => ['fr', 'en'],
            'status' => true,
        ]);

        AiAssistant::firstOrCreate(['assistant_type' => 'SELLER_ASSISTANT'], [
            'name' => 'Assistant Commercial Pièces',
            'description' => 'Expert en catalogue de pièces, compatibilité et tendances du marché.',
            'default_model_id' => $geminiPro->id,
            'language_support' => ['fr', 'en'],
            'status' => true,
        ]);

        AiAssistant::firstOrCreate(['assistant_type' => 'FLEET_ASSISTANT'], [
            'name' => 'Analyste de Flotte IA',
            'description' => 'Spécialisé dans l\'analyse de données de masse et la maintenance prédictive multi-véhicules.',
            'default_model_id' => $geminiPro->id,
            'language_support' => ['fr', 'en'],
            'status' => true,
        ]);

        AiAssistant::firstOrCreate(['assistant_type' => 'BI_ASSISTANT'], [
            'name' => 'Manager Business IA',
            'description' => 'Analyste de performance pour propriétaires de garages.',
            'default_model_id' => $gpt4o->id,
            'language_support' => ['fr', 'en'],
            'status' => true,
        ]);

        AiAssistant::firstOrCreate(['assistant_type' => 'GENERAL_ASSISTANT'], [
            'name' => 'Piiston Copilot',
            'description' => 'Assistant polyvalent pour tous les utilisateurs Piiston.',
            'default_model_id' => $geminiPro->id,
            'language_support' => ['fr', 'en', 'sw'],
            'status' => true,
        ]);

        // 4. Prompt Templates
        AiPromptTemplate::firstOrCreate(['name' => 'auto_diagnosis_v1'], [
            'purpose' => 'DIAGNOSIS',
            'system_prompt' => 'Tu es le Maître Mécanicien Piiston. Analyse les symptômes suivants: {symptoms}. Le véhicule est un {vehicle_brand} {vehicle_model} {vehicle_year}. Kilométrage: {mileage} km. Fournis les causes possibles et actions recommandées sous forme JSON.',
            'variables' => ['symptoms', 'vehicle_brand', 'vehicle_model', 'vehicle_year', 'mileage'],
        ]);

        AiPromptTemplate::firstOrCreate(['name' => 'mechanic_diag_v1'], [
            'purpose' => 'TECHNICAL_DIAGNOSIS',
            'system_prompt' => 'Tu es l\'Expert Technique Piiston pour les professionnels. Analyse le code DTC {dtc_code} et les symptômes {symptoms} pour ce {vehicle_brand} {vehicle_model}. Fournis des étapes de test précises, les valeurs attendues (voltage, résistance) et les schémas à consulter.',
            'variables' => ['dtc_code', 'symptoms', 'vehicle_brand', 'vehicle_model'],
        ]);

        AiPromptTemplate::firstOrCreate(['name' => 'seller_market_v1'], [
            'purpose' => 'MARKET_ANALYSIS',
            'system_prompt' => 'Tu es l\'Assistant Commercial Piiston. Analyse la demande pour la pièce {part_name} compatible avec {vehicle_brand} {vehicle_model}. Donne des conseils de prix et de gestion de stock.',
            'variables' => ['part_name', 'vehicle_brand', 'vehicle_model'],
        ]);
    }
}
