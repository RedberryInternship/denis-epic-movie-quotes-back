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
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/heartbeat', fn () => response()->json(['success' => true]));

Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'email']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'index'])
	->middleware(['signed'])->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
	Route::get('/logout', [AuthController::class, 'logout']);

	Route::get('/newsfeed-quotes', [QuoteController::class, 'index']);

	Route::controller(ProfileController::class)->group(function () {
		Route::get('/user', 'get');
		Route::put('/profile', 'update');
	});

	Route::controller(EmailController::class)->group(function () {
		Route::post('/emails', 'store');
		Route::delete('/emails/{email}', 'destroy');
		Route::post('/emails/make-primary/{email}', 'makePrimary');
	});
});
