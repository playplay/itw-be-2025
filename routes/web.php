<?php

use App\Http\Controllers\GenerateInspiringQuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/inspiring-quote/generate.{format}', GenerateInspiringQuoteController::class);
