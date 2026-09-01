<?php

namespace App\Services\Notifications;

use App\Models\Notifications\NotificationTemplate;

class TemplateService
{
    public function render(string $templateName, array $variables): array
    {
        $template = NotificationTemplate::where('name', $templateName)->first();

        if (! $template) {
            return ['title' => 'Notification', 'body' => 'Message details not found.'];
        }

        $title = $this->replaceVariables($template->title_template, $variables);
        $body = $this->replaceVariables($template->body_template, $variables);

        return [
            'title' => $title,
            'body' => $body,
        ];
    }

    protected function replaceVariables(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $content = str_replace('{'.$key.'}', (string) $value, $content);
        }

        return $content;
    }
}
