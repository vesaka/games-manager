<?php
use Illuminate\Support\Facades\Route;
use Vesaka\Games\Http\Controllers\GameController;

Route::get('{name}', [GameController::class, 'play']);
