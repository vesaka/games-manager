<?php
use Illuminate\Support\Facades\Route;
Route::name('game-app')->get('{name}/{path?}', 'GameController@app');
