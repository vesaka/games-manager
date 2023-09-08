<?php

use Vesaka\Games\Catalogue;

return [
    'identifier' => '_gk',
    'session_key' => 'sid',
    'allow_guest_sessions' => true,
    'guest_email_domain' => '@guest.com',
    'catalogue' => [
        'default' => [
            'strategy' => Catalogue\BaseGame::class,
            'allow_guest_sessions' => true,
            'guest_email_domain' => '@guest.com',
        ],
        'unblockme' => [
            'strategy' => Catalogue\Unblockme::class,
        ],
        'mg' => [
            'strategy' => Catalogue\MemoryGame::class,
        ],

    ],
];
