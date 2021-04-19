<?php

return [
    '/%s/module/wallpaper' => [
        'controller' => 'Admin:Wallpaper@indexAction'
    ],

    '/%s/module/wallpaper/add' => [
        'controller' => 'Admin:Wallpaper@addAction'
    ],

    '/%s/module/wallpaper/save' => [
        'controller' => 'Admin:Wallpaper@saveAction'
    ],

    '/%s/module/wallpaper/edit/(:var)' => [
        'controller' => 'Admin:Wallpaper@editAction'
    ],

    '/%s/module/wallpaper/delete/(:var)' => [
        'controller' => 'Admin:Wallpaper@deleteAction'
    ]
];