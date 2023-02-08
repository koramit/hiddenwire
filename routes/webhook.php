<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/line/{lineBot:channel_secret}', WebhookController::class);
