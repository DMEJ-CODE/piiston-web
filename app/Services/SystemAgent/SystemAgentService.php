<?php

namespace App\Services\SystemAgent;

use App\Models\Reminder;
use App\Services\AI\AIService;
use Illuminate\Support\Facades\Log;

class SystemAgentService
{
    public function __construct(protected AIService $aiService) {}

    /**
     * Evaluate a reminder using the configured AI model (if any) and return the generated message.
     */
    public function evaluateReminder(Reminder $reminder): array
    {
        $aiModel = $reminder->aiModel;

        $prompt = $this->buildPromptForReminder($reminder);

        try {
            if ($aiModel) {
                $response = $this->aiService->chat($aiModel, ['messages' => [['role' => 'system', 'content' => $prompt]]], []);
            } else {
                // fallback: return the body as-is
                $response = ['content' => $reminder->body];
            }
        } catch (\Throwable $e) {
            Log::error('SystemAgent evaluateReminder error: '.$e->getMessage());
            $response = ['content' => $reminder->body ?? ''];
        }

        return $response;
    }

    protected function buildPromptForReminder(Reminder $reminder): string
    {
        $parts = [];
        $parts[] = 'You are a system agent responsible for producing a notification for users.';
        $parts[] = "Reminder title: {$reminder->title}";
        if ($reminder->body) {
            $parts[] = "Reminder body: {$reminder->body}";
        }
        if ($reminder->metadata) {
            $parts[] = 'Metadata: '.json_encode($reminder->metadata);
        }
        $parts[] = 'Produce a concise message suitable for in-app notification (one short paragraph).';

        return implode("\n", $parts);
    }
}
