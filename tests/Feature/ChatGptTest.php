<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\ChatGpt;
use Tests\TestCase;

class ChatGptTest extends TestCase
{
    public function test_get_prompt(): void
    {
        $expected = "I am a chatbot that generates one inspiring quote.
I am very creative.
I reply with the quote directly and I respect the following constraints:
Subject: inspiration
Output language: en";
        $this->assertEquals($expected, (new ChatGpt('fake-api-key'))->getPrompt('inspiration', 'en'));
    }
}
