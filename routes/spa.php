<?php
use Vesaka\Games\Http\Controllers\GameController;
use Vesaka\Games\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//Route::get('unblockme/{path?}/{any?}', [GameController::class, 'spa'])->name('spa');

Route::get('verify-email/{id}/{hash}/{game}', [AuthController::class, 'verifyEmail'])->name('player.verify');
Route::get('{game}/reset-password', [GameController::class, 'spa'])->name('player.reset-password');

$gameKeys = ['unblockme', 'mg'];

foreach($gameKeys as $gameKey) { 
    Route::get($gameKey.'/{path?}/{any?}', [GameController::class, 'spa'])->name('spa');
}
