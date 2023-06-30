<?php
use Vesaka\Games\Http\Controllers\GameController;
use Vesaka\Games\Http\Controllers\AuthController;

Route::get('unblockme/{path?}/{any?}', [GameController::class, 'spa'])->name('spa');

Route::get('verify-email/{id}/{hash}/{game}', [AuthController::class, 'verifyEmail'])->name('player.verify');
Route::get('{game}/reset-password', [GameController::class, 'spa'])->name('player.reset-password');


Route::get('{game}/{path?}/{any?}', [GameController::class, 'spa'])->name('spa');