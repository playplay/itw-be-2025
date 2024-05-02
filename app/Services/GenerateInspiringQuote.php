<?php

namespace App\Services;

class GenerateInspiringQuote
{
    private const string FORMAT_TXT = 'txt';
    private const string FORMAT_JSON = 'json';
    private const string FORMAT_XML = 'xml';

    private int $numberOfTokens = 0;

    public function __construct(private readonly string $apiKey) {}

    public function generate(int $number, string $subject, string $language, int $minCharacters, string $format, string $model, float $temperature): string
    {
        $inspiringQuotes = [];

        for ($i = 0; $i < $number; $i++) {
            if ($format !== self::FORMAT_TXT && $format !== self::FORMAT_JSON && $format !== self::FORMAT_XML) {
                throw new \InvalidArgumentException('Invalid format');
            }

            $response = $this->callChatGPT($subject, $language, $minCharacters, $model, $temperature);
            $this->numberOfTokens += $response['usage']['total_tokens'];
            $inspiringQuotes[] = $response['choices'][0]['message']['content'];
        }

        return $this->format($inspiringQuotes, $format);
    }

    public function getNumberOfTokens(): int
    {
        return $this->numberOfTokens;
    }

    public function callChatGPT(
        string $subject,
        string $language,
        int $minCharacters,
        string $model,
        float $temperature
    ): mixed {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->getSystemMessage($subject, $language, $minCharacters),
                ]
            ],
            'model' => $model,
            'temperature' => $temperature,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);

        return $response;
    }

    public function getSystemMessage(string $subject, string $language, int $minCharacters): string
    {
        $message = "I am a chatbot that generates one inspiring quote.\n";
        $message .= "I am very creative.\n";
        $message .= "I reply with the quote directly and I respect the following constraints:\n";
        $message .= "Subject: $subject\n";
        $message .= "Output language: $language\n";
        $message .= "Minimum characters: $minCharacters";

        return $message;
    }

    public function format(array $inspiringQuotes, string $format): string
    {
        if ($format === self::FORMAT_JSON) {
            return json_encode($inspiringQuotes);
        } else {
            if ($format === self::FORMAT_XML) {
                $xml = '<?xml version="1.0" encoding="UTF-8"?><inspiring-quotes>';
                foreach ($inspiringQuotes as $inspiringQuote) {
                    $xml .= "<inspiring-quote>$inspiringQuote</inspiring-quote>";
                }

                $xml .= '</inspiring-quotes>';

                return $xml;
            } else {
                $txt = '';
                foreach ($inspiringQuotes as $inspiringQuote) {
                    $txt .= "$inspiringQuote\n";
                }

                return $txt;
            }
        }
    }
}
