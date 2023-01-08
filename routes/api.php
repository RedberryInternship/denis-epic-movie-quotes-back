<?php

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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'index'])
	->middleware(['signed'])->name('verification.verify');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return auth()->user();
});
