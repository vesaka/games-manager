<?php

Route::namespace('Admin')
    ->middleware('auth')
    ->group(function () {
        Route::resource('game', 'GameController');
    });
