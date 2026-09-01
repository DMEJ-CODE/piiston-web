<?php

use App\AI\Adapters\GeminiAdapter;
use App\Models\AI\AiModel;
use App\Models\AI\AiProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

test('it can chat with gemini', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [['text' => 'Hello from Gemini!']],
                    ],
                ],
            ],
            'usageMetadata' => [
                'promptTokenCount' => 10,
                'candidatesTokenCount' => 20,
                'totalTokenCount' => 30,
            ],
        ], 200),
    ]);

    Config::set('services.gemini.key', 'test-key');

    $provider = AiProvider::create([
        'name' => 'Gemini',
        'provider_code' => 'GEMINI',
        'status' => true,
    ]);

    $model = AiModel::create([
        'provider_id' => $provider->id,
        'model_name' => 'gemini-1.5-flash',
        'purpose' => 'GENERAL',
        'status' => true,
    ]);

    $adapter = new GeminiAdapter;
    $response = $adapter->chat($model, [
        ['role' => 'system', 'content' => 'You are an expert.'],
        ['role' => 'user', 'content' => 'Hi'],
    ]);

    Http::assertSent(function ($request) {
        return isset($request['system_instruction']) &&
               $request['system_instruction']['parts'][0]['text'] === 'You are an expert.' &&
               count($request['contents']) === 1;
    });

    expect($response['content'])->toBe('Hello from Gemini!');
    expect($response['usage']['total_tokens'])->toBe(30);
});
