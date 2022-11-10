<?php

use App\Http\Controllers\Api\MailApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/{any}', function () {
    return view('welcome');
});

Route::post('mail-confirmation', [MailApiController::class, 'confirmation'])->middleware(['myHeader']);

Route::get('account/verify/{token}', [MailApiController::class, 'verifyAccount'])->name('user.verify');