<?php

Route::name('game-app')->get('{name}/{path?}', 'GameController@app');


