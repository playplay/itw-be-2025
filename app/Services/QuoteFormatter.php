<?php

declare(strict_types=1);

namespace App\Services;

class QuoteFormatter
{
    private const string FORMAT_TXT = 'txt';
    private const string FORMAT_JSON = 'json';
    private const string FORMAT_XML = 'xml';

    public static function isInvalid(string $format): bool
    {
        return $format !== self::FORMAT_TXT && $format !== self::FORMAT_JSON && $format !== self::FORMAT_XML;
    }

    public function format(array $inspiringQuotes, string $format): string
    {
        if ($format === self::FORMAT_JSON) {
            return $this->formatJson($inspiringQuotes);
        } else {
            if ($format === self::FORMAT_XML) {
                return $this->formatXml($inspiringQuotes);
            } else {
                return $this->formatTxt($inspiringQuotes);
            }
        }
    }

    public function formatJson(array $inspiringQuotes): string
    {
        return json_encode($inspiringQuotes);
    }

    public function formatXml(array $inspiringQuotes): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><inspiring-quotes>';
        $xml .= implode("\n", array_map(fn($quote) => "<inspiring-quote>$quote</inspiring-quote>", $inspiringQuotes));
        $xml .= '</inspiring-quotes>';

        return $xml;
    }

    public function formatTxt(array $inspiringQuotes): string
    {
        return implode("\n", $inspiringQuotes);
    }
}
