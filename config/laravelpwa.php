<?php

return [

    'manifest' => [
        'name' => 'GoldenRise INVEST',
        'short_name' => 'GoldenRise',
        'start_url' => '/dashboard',
        'background_color' => '#ffffff',
        'theme_color' => '#059669',
        'display' => 'standalone',
        'orientation' => 'portrait',
        'status_bar' => 'black',
        'icons' => [
            '192x192' => [
                'path' => '/assets/icons/pwa/icon-192x192.png',
                'purpose' => 'any',
            ],
            '512x512' => [
                'path' => '/assets/icons/pwa/icon-512x512.png',
                'purpose' => 'any',
            ],
        ],
        'splash' => [],
        'custom' => [],
    ],

];