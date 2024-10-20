<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\BookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::get('/book-list', [BookController::class, 'index'])->name('book.list');
