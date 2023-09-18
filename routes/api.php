<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Vesaka\Games\Http\Controllers\AuthController;
use Vesaka\Games\Http\Controllers\GameSessionController;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('forgot-password');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::get('leaderboard', [GameSessionController::class, 'leaderboard'])->name('leaderboard');

Route::post('guest', [AuthController::class, 'createGuest'])->name('guest');
Route::match(['get', 'post'], 'test-encryption', [GameSessionController::class, 'testEncryption'])->name('test-encryption');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('/play')->group(function () {
        Route::post('/start', [GameSessionController::class, 'start']);
        Route::post('end', [GameSessionController::class, 'end']);
    })->name('play.');

    Route::get('/player', function (Request $request) {
        $user = $request->user()->only('id', 'name');
        $user['token'] = $request->user()->currentAccessToken()->plainTextToken;

        return $user;
    })->name('user');
});
