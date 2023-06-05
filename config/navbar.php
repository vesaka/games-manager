<?php

return [
    'menu' => [
        [
            'label' => 'Games',
            'name' => 'games',
            'icon' => 'game',
            'children' => [
                [
                    'name' => 'new-game',
                    'label' => 'New Game',
                    'icon' => 'add',
                    'route' => 'admin::game.create',
                ],
                [
                    'name' => 'games',
                    'label' => 'Games',
                    'icon' => 'add',
                    'route' => 'admin::game.index',
                ],
            ],
        ],
    ],
];
