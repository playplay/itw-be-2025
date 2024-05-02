<?php

namespace App\Http\Controllers;

use App\Services\GenerateInspiringQuote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GenerateInspiringQuoteController extends Controller
{
    public function __invoke(string $format, Request $request): Response
    {
        $service = new GenerateInspiringQuote('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');

        $inspiringQuotes = $service->generate(
            $request->integer('number'),
            $request->string('subject'),
            $request->string('language'),
            $request->integer('min_characters'),
            $format,
            'gpt-3.5-turbo',
            1.0,
        );

        DB::statement('INSERT INTO openai_log VALUES (:requested_at, :tokens, :subject)', [
            'requested_at' => (new \DateTimeImmutable())->format('Uv'),
            'tokens' => $service->getNumberOfTokens(),
            'subject' => $request->string('subject'),
        ]);

        $contentType = match ($format) {
            'json' => 'application/json',
            'xml' => 'application/xml',
            'txt' => 'text/plain',
        };

        return new Response($inspiringQuotes, 200, [
            'Content-Type' => $contentType,
        ]);
    }
}
