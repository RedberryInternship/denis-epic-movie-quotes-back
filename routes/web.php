<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/oauth/redirect', [GoogleController::class, 'redirect']);
Route::get('/oauth/callback', [GoogleController::class, 'callback']);
