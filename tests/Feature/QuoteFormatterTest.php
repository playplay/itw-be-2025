<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\QuoteFormatter;
use Tests\TestCase;

class QuoteFormatterTest extends TestCase
{
    public function test_format_in_txt(): void
    {
        $this->assertEquals("quote 1\nquote 2\nquote 3", $this->getResult('txt'));
    }

    public function test_format_in_json(): void
    {
        $this->assertEquals('["quote 1","quote 2","quote 3"]', $this->getResult('json'));
    }

    public function test_format_in_xml(): void
    {
        $expected = <<<EXML
<?xml version="1.0" encoding="UTF-8"?>
<inspiring-quotes>
    <inspiring-quote>quote 1</inspiring-quote>
    <inspiring-quote>quote 2</inspiring-quote>
    <inspiring-quote>quote 3</inspiring-quote>
</inspiring-quotes>
EXML;
        $this->assertXmlStringEqualsXmlString($expected, $this->getResult('xml'));
    }

    protected function getResult(string $format): string
    {
        return (new QuoteFormatter())->format(['quote 1', 'quote 2', 'quote 3'], $format);
    }
}
