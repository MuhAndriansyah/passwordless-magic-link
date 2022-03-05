<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['guest'])->group(function() {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login/magic-link', [AuthController::class, 'store'])->name('magic-link');

    Route::get('verify-login', [AuthController::class, 'verifyToken'])->name('verify-login');
});
