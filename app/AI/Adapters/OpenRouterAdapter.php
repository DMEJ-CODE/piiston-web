<?php

namespace App\AI\Adapters;

use App\AI\Contracts\AIProviderInterface;
use App\Models\AI\AiModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterAdapter implements AIProviderInterface
{
    public function chat(AiModel $model, array $messages, array $options = []): array
    {
        $apiKey = config('services.openrouter.key') ?? env('OPENROUTER_API_KEY');
        $modelName = $options['model_name'] ?? ($model->model_name ?? 'google/gemini-2.0-flash-001');

        if (empty($apiKey)) {
            Log::warning('OpenRouter API Key is missing.');

            return [
                'error' => true,
                'content' => 'Clé API OpenRouter non configurée.',
                'usage' => ['total_tokens' => 0],
                'latency_ms' => 0,
            ];
        }

        try {
            $start = microtime(true);
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'HTTP-Referer' => config('app.url', 'https://piiston.com'),
                    'X-Title' => 'Piiston AI',
                    'Content-Type' => 'application/json',
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $modelName,
                    'messages' => $messages,
                    'temperature' => $options['temperature'] ?? 0.7,
                    'max_tokens' => $options['max_tokens'] ?? 2048,
                ]);

            $latency = (int) ((microtime(true) - $start) * 1000);

            if ($response->failed()) {
                Log::error('OpenRouter API Error: '.$response->body());

                return [
                    'error' => true,
                    'content' => 'Erreur lors de la communication avec OpenRouter.',
                    'usage' => ['total_tokens' => 0],
                    'latency_ms' => $latency,
                ];
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (! $content) {
                return [
                    'error' => true,
                    'content' => 'Réponse vide de la part d\'OpenRouter.',
                    'usage' => ['total_tokens' => 0],
                    'latency_ms' => $latency,
                ];
            }

            return [
                'error' => false,
                'content' => $content,
                'usage' => [
                    'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                    'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                    'total_tokens' => $data['usage']['total_tokens'] ?? 0,
                ],
                'latency_ms' => $latency,
            ];
        } catch (\Exception $e) {
            Log::error('OpenRouter Adapter Exception: '.$e->getMessage());

            return [
                'error' => true,
                'content' => 'Erreur de connexion à OpenRouter : '.$e->getMessage(),
                'usage' => ['total_tokens' => 0],
                'latency_ms' => 0,
            ];
        }
    }

    public function embed(AiModel $model, string $text): array
    {
        return [];
    }
}
