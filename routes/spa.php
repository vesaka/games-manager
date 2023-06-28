<?php
use Vesaka\Games\Http\Controllers\GameController;

Route::get('unblockme/{path?}/{any?}', [GameController::class, 'spa']);

