<?php

namespace App\AI\Contracts;

use App\Models\AI\AiModel;

interface AIProviderInterface
{
    /**
     * Send a list of messages to the AI model and get a response.
     */
    public function chat(AiModel $model, array $messages, array $options = []): array;

    /**
     * Generate an embedding for the given text.
     */
    public function embed(AiModel $model, string $text): array;
}
