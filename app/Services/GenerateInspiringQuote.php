<?php

namespace App\Services;

class GenerateInspiringQuote
{
    public function __construct(private readonly string $apiKey)
    {
    }

    public function generate(int $number, string $subject, string $language, string $format): string
    {
        $inspiringQuotes = [];
        for ($i = 0; $i < $number; $i++) {
            if (QuoteFormatter::isInvalid($format)) {
                throw new \InvalidArgumentException('Invalid format');
            }

            $response = (new ChatGpt($this->apiKey))->call($subject, $language);
            $inspiringQuotes[] = $response['choices'][0]['message']['content'];
        }

        return (new QuoteFormatter())->format($inspiringQuotes, $format);
    }
}
