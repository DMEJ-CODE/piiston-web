<?php

namespace App\Services\AI;

use App\AI\Adapters\GeminiAdapter;
use App\AI\Adapters\OpenAIAdapter;
use App\AI\Adapters\OpenRouterAdapter;
use App\AI\Contracts\AIProviderInterface;
use App\Models\AI\AiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Get the appropriate adapter for a given model
     */
    public function getAdapter(AiModel $model): AIProviderInterface
    {
        $providerCode = $model->provider->provider_code;

        return match ($providerCode) {
            'OPENAI' => new OpenAIAdapter,
            'GEMINI' => new GeminiAdapter,
            'OPENROUTER' => new OpenRouterAdapter,
            default => throw new \Exception("AI Provider {$providerCode} not supported yet."),
        };
    }

    /**
     * Unified chat entry point with automatic Gemini -> OpenRouter fallback
     */
    public function chat(AiModel $model, array $messages, array $options = []): array
    {
        $providerCode = $model->provider->provider_code;
        $adapter = $this->getAdapter($model);
        $response = $adapter->chat($model, $messages, $options);

        // If Gemini fails, hits token limit, or errors out, automatically fallback to OpenRouter
        if ($providerCode === 'GEMINI' && (! empty($response['error']) || empty($response['content']))) {
            Log::warning('Gemini failed or hit token/rate limit. Falling back to OpenRouter automatically.');
            try {
                $openRouterAdapter = new OpenRouterAdapter;
                $response = $openRouterAdapter->chat($model, $messages, $options);
            } catch (\Exception $e) {
                Log::error('OpenRouter fallback exception: '.$e->getMessage());
            }
        }

        // Log usage for cost tracking
        Auth::user()?->aiUsageLogs()->create([
            'model_id' => $model->id,
            'assistant_id' => $options['assistant_id'] ?? null,
            'tokens_used' => $response['usage']['total_tokens'] ?? 0,
            'execution_time_ms' => $response['latency_ms'] ?? 0,
            'estimated_cost' => 0.0001,
        ]);

        return $response;
    }
}
