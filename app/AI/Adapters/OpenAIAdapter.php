<?php

namespace App\AI\Adapters;

use App\AI\Contracts\AIProviderInterface;
use App\Models\AI\AiModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIAdapter implements AIProviderInterface
{
    public function chat(AiModel $model, array $messages, array $options = []): array
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        $modelName = $model->model_name ?? 'gpt-4o';

        if (empty($apiKey)) {
            Log::warning('OpenAI API Key is missing.');

            return [
                'content' => json_encode([
                    'causes' => ['Vérification générale du système nécessaire', 'Connexion API OpenAI non configurée (clé API manquante)'],
                    'actions' => ['Ajouter OPENAI_API_KEY ou GEMINI_API_KEY dans le fichier .env', 'Tester les composants principaux'],
                    'urgency' => 'MEDIUM',
                    'confidence' => 75,
                ]),
                'usage' => ['total_tokens' => 0],
                'latency_ms' => 0,
            ];
        }

        try {
            $start = microtime(true);
            $response = Http::timeout(30)
                ->withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $modelName,
                    'messages' => $messages,
                    'temperature' => $options['temperature'] ?? 0.7,
                    'max_tokens' => $options['max_tokens'] ?? 2048,
                ]);
            $latency = (int) ((microtime(true) - $start) * 1000);

            if ($response->failed()) {
                Log::error('OpenAI API Error: '.$response->body());

                return [
                    'content' => json_encode([
                        'causes' => ['Erreur de communication avec l\'API OpenAI'],
                        'actions' => ['Vérifier la clé API et le quota OpenAI'],
                        'urgency' => 'MEDIUM',
                        'confidence' => 50,
                    ]),
                    'usage' => ['total_tokens' => 0],
                    'latency_ms' => $latency,
                ];
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? 'Pas de réponse de l\'IA.';

            return [
                'content' => $content,
                'usage' => [
                    'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                    'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                    'total_tokens' => $data['usage']['total_tokens'] ?? 0,
                ],
                'latency_ms' => $latency,
            ];
        } catch (\Exception $e) {
            Log::error('OpenAI Adapter Exception: '.$e->getMessage());

            return [
                'content' => json_encode([
                    'causes' => ['Exception de connexion à l\'API OpenAI'],
                    'actions' => ['Vérifier la connectivité réseau du serveur backend'],
                    'urgency' => 'MEDIUM',
                    'confidence' => 50,
                ]),
                'usage' => ['total_tokens' => 0],
                'latency_ms' => 0,
            ];
        }
    }

    public function embed(AiModel $model, string $text): array
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            return ['embedding' => array_fill(0, 1536, 0.0), 'usage' => ['total_tokens' => 0]];
        }

        $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/embeddings', [
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);

        if ($response->failed()) {
            return [];
        }

        return [
            'embedding' => $response->json('data.0.embedding'),
            'usage' => $response->json('usage', ['total_tokens' => 0]),
        ];
    }
}
