<?php

namespace App\AI\Adapters;

use App\AI\Contracts\AIProviderInterface;
use App\Models\AI\AiModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAdapter implements AIProviderInterface
{
    /**
     * Send a list of messages to the AI model and get a response.
     */
    public function chat(AiModel $model, array $messages, array $options = []): array
    {
        $apiKey = config('services.gemini.key');
        $modelName = $model->model_name ?? 'gemini-flash-lite-latest';

        if (empty($apiKey)) {
            Log::warning('Gemini API Key is missing.');

            return [
                'error' => true,
                'content' => "Désolé, l'assistant IA n'est pas configuré. Veuillez ajouter GEMINI_API_KEY dans le fichier .env.",
                'usage' => ['total_tokens' => 0],
                'latency_ms' => 0,
            ];
        }

        // Extract system instruction if present
        $systemMessage = collect($messages)->firstWhere('role', 'system');
        $contents = collect($messages)
            ->reject(fn ($msg) => $msg['role'] === 'system')
            ->map(function ($msg) {
                $role = match ($msg['role']) {
                    'assistant' => 'model',
                    default => 'user',
                };

                return [
                    'role' => $role,
                    'parts' => [['text' => $msg['content']]],
                ];
            })
            ->values()
            ->toArray();

        try {
            $payload = [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? 2048,
                ],
            ];

            if ($systemMessage) {
                $payload['system_instruction'] = [
                    'parts' => [['text' => $systemMessage['content']]],
                ];
            }

            $start = microtime(true);
            $response = Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}", $payload);
            $latency = (int) ((microtime(true) - $start) * 1000);

            if ($response->failed()) {
                Log::error('Gemini API Error: '.$response->body());

                return [
                    'error' => true,
                    'content' => "Une erreur est survenue lors de la communication avec l'IA.",
                    'usage' => ['total_tokens' => 0],
                    'latency_ms' => $latency,
                ];
            }

            $data = $response->json();
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (! $content) {
                return [
                    'error' => true,
                    'content' => 'Pas de réponse de l\'IA.',
                    'usage' => ['total_tokens' => 0],
                    'latency_ms' => $latency,
                ];
            }

            return [
                'error' => false,
                'content' => $content,
                'usage' => [
                    'prompt_tokens' => $data['usageMetadata']['promptTokenCount'] ?? 0,
                    'completion_tokens' => $data['usageMetadata']['candidatesTokenCount'] ?? 0,
                    'total_tokens' => $data['usageMetadata']['totalTokenCount'] ?? 0,
                ],
                'latency_ms' => $latency,
            ];
        } catch (\Exception $e) {
            Log::error('Gemini Adapter Exception: '.$e->getMessage());

            return [
                'error' => true,
                'content' => "Erreur de connexion à l'IA.",
                'usage' => ['total_tokens' => 0],
                'latency_ms' => 0,
            ];
        }
    }

    /**
     * Generate an embedding for the given text.
     */
    public function embed(AiModel $model, string $text): array
    {
        $apiKey = config('services.gemini.key');

        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/embedding-001:embedContent?key={$apiKey}", [
            'model' => 'models/embedding-001',
            'content' => ['parts' => [['text' => $text]]],
        ]);

        if ($response->failed()) {
            return [];
        }

        return [
            'embedding' => $response->json('embedding.values'),
            'usage' => ['total_tokens' => 0],
        ];
    }
}
