<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SePayController;

Route::post('/sepay/webhook', [SePayController::class, 'webhook'])
    ->name('sepay.webhook');