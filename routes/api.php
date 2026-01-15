<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeWebhookController;

Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook']);
