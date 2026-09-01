<?php

namespace App\Services\AI;

use App\Models\AI\AiPromptTemplate;

class PromptEngine
{
    /**
     * Build a prompt from a template and variables
     */
    public function buildPrompt(AiPromptTemplate $template, array $data)
    {
        $prompt = $template->system_prompt;

        foreach ($data as $key => $value) {
            $prompt = str_replace('{'.$key.'}', $value, $prompt);
        }

        return $prompt;
    }
}
