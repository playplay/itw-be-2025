<?php

namespace App\Http\Controllers;

use App\Services\GenerateInspiringQuote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenerateInspiringQuoteController extends Controller
{
    public function __invoke(string $format, Request $request): Response
    {
        $service = new GenerateInspiringQuote('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');

        $inspiringQuotes = $service->generate(
            $request->integer('number'),
            $request->string('subject'),
            $request->string('language'),
            $format,
        );

        return new Response($inspiringQuotes, 200);
    }
}
