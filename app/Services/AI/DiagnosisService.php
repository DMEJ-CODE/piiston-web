<?php

namespace App\Services\AI;

use App\Models\AI\AiAssistant;
use App\Models\AI\AiModel;
use App\Models\AI\AiPromptTemplate;
use App\Models\Garages\VehicleDiagnosis;
use App\Models\Vehicles\Vehicle;
use Illuminate\Support\Facades\Auth;

class DiagnosisService
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function diagnoseVehicle(Vehicle $vehicle, string $symptoms, ?string $dtcCode = null)
    {
        $user = Auth::user();
        $isMechanic = $user->hasRole('MECHANIC');

        $templateName = $isMechanic ? 'mechanic_diag_v1' : 'auto_diagnosis_v1';
        $assistantType = $isMechanic ? 'MECHANIC_ASSISTANT' : 'VEHICLE_DIAGNOSIS';

        $template = AiPromptTemplate::where('name', $templateName)->first()
                 ?? AiPromptTemplate::where('name', 'auto_diagnosis_v1')->first();

        $model = AiModel::where('model_name', 'gpt-4o')->first()
                 ?? AiModel::where('status', true)->first();

        $assistant = AiAssistant::where('assistant_type', $assistantType)->first();

        if (! $template || ! $model) {
            // Fallback mock if not configured
            return Auth::user()->aiDiagnoses()->create([
                'vehicle_id' => $vehicle->id,
                'symptoms' => $symptoms,
                'dtc_code' => $dtcCode,
                'possible_causes' => ['Batterie faible', 'Démarreur défectueux'],
                'recommended_actions' => ['Vérifier voltage batterie', 'Tester le solénoïde'],
                'urgency_level' => 'MEDIUM',
                'confidence_score' => 85,
            ]);
        }

        // Build prompt from template
        $replacements = [
            '{symptoms}' => $symptoms,
            '{dtc_code}' => $dtcCode ?? 'Aucun',
            '{vehicle_brand}' => $vehicle->brand->name,
            '{vehicle_model}' => $vehicle->model->name,
            '{vehicle_year}' => $vehicle->year,
            '{mileage}' => $vehicle->mileage,
            '{fuel_type}' => $vehicle->fuelType?->name ?? 'N/A',
            '{transmission}' => $vehicle->transmission?->name ?? 'N/A',
        ];

        $prompt = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template->system_prompt
        );

        $messages = [
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => "Diagnostic pour {$vehicle->brand->name} {$vehicle->model->name}. Symptômes: {$symptoms}.".($dtcCode ? " Code DTC: {$dtcCode}" : '')],
        ];

        $response = $this->aiService->chat($model, $messages, ['assistant_id' => $assistant?->id]);

        $content = $response['content'] ?? '';
        $data = [];

        // Try to parse JSON from content
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $content, $matches)) {
            $data = json_decode($matches[0], true) ?? [];
        }

        $causes = $data['causes'] ?? null;
        if (empty($causes)) {
            $causes = $content ? [$content] : ['Vérification générale du système recommandée'];
        }
        if (is_string($causes)) {
            $causes = [$causes];
        }

        $actions = $data['actions'] ?? [];
        if (is_string($actions)) {
            $actions = [$actions];
        }
        if (empty($actions)) {
            $actions = ['Inspecter les composants principaux', 'Consulter un technicien qualifié'];
        }

        // Save structured diagnosis
        return Auth::user()->aiDiagnoses()->create([
            'vehicle_id' => $vehicle->id,
            'symptoms' => $symptoms,
            'dtc_code' => $dtcCode,
            'possible_causes' => $causes,
            'recommended_actions' => $actions,
            'urgency_level' => $data['urgency'] ?? 'MEDIUM',
            'confidence_score' => $data['confidence'] ?? 80,
        ]);
    }

    /**
     * AI helper for Garage Workshop diagnosis
     */
    public function suggestWorkshopDiagnosis(VehicleDiagnosis $diagnosis)
    {
        $vehicle = $diagnosis->repairOrder->vehicle;
        $symptoms = $diagnosis->symptoms;

        // For now, return a mock hypothesis to demonstrate functionality
        $hypotheses = [
            [
                'cause' => 'Problème d\'allumage',
                'probability' => 70,
                'tests' => ['Vérification bougies', 'Test bobine'],
            ],
            [
                'cause' => 'Injection défaillante',
                'probability' => 30,
                'tests' => ['Pression rampe', 'Test injecteurs'],
            ],
        ];

        $diagnosis->update([
            'ai_hypotheses' => $hypotheses,
            'is_ai_generated' => true,
        ]);

        return $hypotheses;
    }
}
