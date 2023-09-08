<?php

use Vesaka\Games\Catalogue;

return [
    'identifier' => '_gk',
    'session_key' => 'sid',
    'allow_guest_sessions' => false,
    'catalogue' => [
        'unblockme' => [
            'strategy' => Catalogue\Unblockme::class,
        ],
        'mg' => [
            'strategy' => Catalogue\MemoryGame::class,
        ],

    ],
];
