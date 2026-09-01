<?php

namespace App\Services\AI;

use App\AI\Adapters\GeminiAdapter;
use App\AI\Adapters\OpenAIAdapter;
use App\AI\Contracts\AIProviderInterface;
use App\Models\AI\AiModel;
use Illuminate\Support\Facades\Auth;

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
            default => throw new \Exception("AI Provider {$providerCode} not supported yet."),
        };
    }

    /**
     * Unified chat entry point
     */
    public function chat(AiModel $model, array $messages, array $options = []): array
    {
        $adapter = $this->getAdapter($model);
        $response = $adapter->chat($model, $messages, $options);

        // Log usage for cost tracking
        Auth::user()?->aiUsageLogs()->create([
            'model_id' => $model->id,
            'assistant_id' => $options['assistant_id'] ?? null,
            'tokens_used' => $response['usage']['total_tokens'] ?? 0,
            'execution_time_ms' => $response['latency_ms'] ?? 0,
            'estimated_cost' => 0.0001, // Logic for cost calc goes here
        ]);

        return $response;
    }
}
