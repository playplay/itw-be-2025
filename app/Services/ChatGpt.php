<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatGpt
{
    public function __construct(private string $apiKey)
    {
    }

    public function call(string $subject, string $language): mixed
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->getPrompt($subject, $language),
                ],
            ],
            'model' => 'gpt-3.5-turbo',
            'temperature' => 1.0,
        ]);

        return $response->json();
    }

    public function getPrompt(string $subject, string $language): string
    {
        return <<<EGPT
I am a chatbot that generates one inspiring quote.
I am very creative.
I reply with the quote directly and I respect the following constraints:
Subject: $subject
Output language: $language
EGPT;
    }
}
