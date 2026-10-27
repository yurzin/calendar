<?php

use App\Http\Controllers\Api\MailApiController;
use Illuminate\Support\Facades\Route;

Route::post('mail-confirmation', [MailApiController::class, 'confirmation'])
    ->middleware(['throttle:60,1', 'webhook.key']);

Route::get('account/verify/{token}', [MailApiController::class, 'verifyAccount'])
    ->where('token', '[A-Za-z0-9]{64}')
    ->middleware('throttle:30,1')
    ->name('user.verify');
