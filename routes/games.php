<?php
use Vesaka\Games\Http\Controllers\GameController;
Route::get('{name}', [GameController::class, 'play']);