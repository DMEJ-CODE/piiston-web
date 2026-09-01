<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Controller;
use App\Models\AI\AiAssistant;
use App\Models\AI\AiConversation;
use App\Models\AI\AiModel;
use App\Models\Vehicles\Vehicle;
use App\Services\AI\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssistantController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function assistants()
    {
        return response()->json(AiAssistant::where('status', true)->get());
    }

    public function conversations()
    {
        return response()->json(
            Auth::user()->aiConversations()->with('assistant')->orderBy('updated_at', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assistant_id' => 'required|exists:ai_assistants,id',
        ]);

        $conversation = Auth::user()->aiConversations()->create([
            'assistant_id' => $validated['assistant_id'],
            'title' => 'Nouvelle conversation',
            'started_at' => now(),
        ]);

        return response()->json($conversation, 201);
    }

    public function chat(Request $request, AiConversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ]);

        // Save user message
        $conversation->messages()->create([
            'sender_type' => 'USER',
            'content' => $request->message,
        ]);

        // Get recent history for context
        $history = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse()
            ->values()
            ->map(fn ($msg) => [
                'role' => $msg->sender_type === 'AI' ? 'assistant' : 'user',
                'content' => $msg->content,
            ])
            ->toArray();

        // Inject system persona if assistant has a description
        $systemPrompt = $conversation->assistant->description ?? 'Tu es l\'expert en chef de Piiston.';

        // Add Vehicle Context if provided
        if ($request->vehicle_id) {
            $vehicle = Vehicle::with(['brand', 'model', 'fuelType', 'transmission'])->find($request->vehicle_id);
            if ($vehicle && $vehicle->owner_id === Auth::id()) {
                $vehicleContext = "\n\nCONTEXTE VÉHICULE ACTUEL :\n".
                    "- Marque: {$vehicle->brand->name}\n".
                    "- Modèle: {$vehicle->model->name}\n".
                    "- Année: {$vehicle->year}\n".
                    "- Kilométrage: {$vehicle->mileage} km\n";

                if ($vehicle->fuelType) {
                    $vehicleContext .= "- Énergie: {$vehicle->fuelType->name}\n";
                }
                if ($vehicle->transmission) {
                    $vehicleContext .= "- Transmission: {$vehicle->transmission->name}\n";
                }
                if ($vehicle->color) {
                    $vehicleContext .= "- Couleur: {$vehicle->color}\n";
                }

                $systemPrompt .= $vehicleContext."\nL'utilisateur pose une question concernant ce véhicule spécifique. Utilise ces informations techniques pour tes réponses, mais ne mentionne pas d'informations privées comme la plaque d'immatriculation ou le VIN.";
            }
        }

        array_unshift($history, [
            'role' => 'system',
            'content' => $systemPrompt,
        ]);

        // Use assistant's default model or fallback
        $model = $conversation->assistant->defaultModel ?? AiModel::where('status', true)->first();

        if (! $model) {
            return response()->json(['message' => 'No AI model configured'], 500);
        }

        $response = $this->aiService->chat($model, $history, [
            'assistant_id' => $conversation->assistant_id,
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'AI',
            'content' => $response['content'],
            'input_tokens' => $response['usage']['prompt_tokens'] ?? 0,
            'output_tokens' => $response['usage']['completion_tokens'] ?? 0,
        ]);

        return response()->json($message);
    }
}
