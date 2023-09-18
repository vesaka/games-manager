<?php
use Illuminate\Support\Facades\Route;
use Vesaka\Games\Http\Controllers\{GameController, AuthController};
Route::get('verify-email/{id}/{hash}/{game}', [AuthController::class, 'verifyEmail'])->name('player.verify');
Route::get('{game}/reset-password', [GameController::class, 'spa'])->name('player.reset-password');
Route::name('game-app')->get('{name}/{path?}', 'GameController@app');
