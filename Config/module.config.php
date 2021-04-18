<?php

return [
    'name'  => 'Wallpaper',
    'description' => 'Manage and sell wallpapers on-line',
    'menu' => [
        'name' => 'Wallpaper',
        'icon' => 'fas fa-scroll',
        'items' => [
            [
                'route' => 'Wallpaper:Admin:Wallpaper@indexAction',
                'name' => 'View all wallpapers'
            ],

            [
                'route' => 'Wallpaper:Admin:Wallpaper@addAction',
                'name' => 'Add new wallpaper'
            ]
        ]
    ]
];