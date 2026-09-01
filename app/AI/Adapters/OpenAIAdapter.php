<?php

namespace App\AI\Adapters;

use App\AI\Contracts\AIProviderInterface;
use App\Models\AI\AiModel;
use Illuminate\Support\Facades\Log;

class OpenAIAdapter implements AIProviderInterface
{
    public function chat(AiModel $model, array $messages, array $options = []): array
    {
        Log::info("OpenAI Chat with model: {$model->model_name}");

        // In a real implementation, we would call OpenAI API here.
        // For now, we simulate a response to ensure the architecture works.

        return [
            'content' => "Simulated response from OpenAI ({$model->model_name}) based on your prompt.",
            'usage' => [
                'prompt_tokens' => 100,
                'completion_tokens' => 150,
                'total_tokens' => 250,
            ],
            'latency_ms' => 1200,
        ];
    }

    public function embed(AiModel $model, string $text): array
    {
        return [
            'embedding' => array_fill(0, 1536, 0.0), // Simulated vector
            'usage' => ['total_tokens' => 10],
        ];
    }
}
