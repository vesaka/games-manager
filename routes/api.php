<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Vesaka\Games\Http\Controllers\GameSessionController;
use Vesaka\Games\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('send-password-reset-link', [AuthController::class, 'sendPasswordResetLink']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::get('leaderboard', [GameSessionController::class, 'leaderboard']);

Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::prefix('/play')->group(function() {
        Route::post('/start', [GameSessionController::class, 'start']);
        Route::post('end', [GameSessionController::class, 'end']);
    })->name('play.');
    
    Route::get('/player', function (Request $request) {
        $user = $request->user()->only('id', 'name');
        $user['token'] = $request->user()->currentAccessToken();
        return $user;
    })->name('user'); 
});
 
