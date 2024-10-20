<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'register']);
    Route::resource('/books', \App\Http\Controllers\BookController::class)->only('index', 'show');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\LoginController::class, 'logout']);
        Route::resource('/books', \App\Http\Controllers\BookController::class)->except('index', 'show');
    });
});
