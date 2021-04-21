<?php

return [
    // Wallpaper
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
    ],

    // Interior
    '/%s/module/wallpaper/interior/add/(:var)' => [
        'controller' => 'Admin:Interior@addAction'
    ],

    '/%s/module/wallpaper/interior/edit/(:var)' => [
        'controller' => 'Admin:Interior@editAction'
    ],

    '/%s/module/wallpaper/interior/delete/(:var)' => [
        'controller' => 'Admin:Interior@deleteAction'
    ],

    '/%s/module/wallpaper/interior/save' => [
        'controller' => 'Admin:Interior@saveAction'
    ],
    
    // Gallery
    '/%s/module/wallpaper/gallery/add/(:var)' => [
        'controller' => 'Admin:Gallery@addAction'
    ],

    '/%s/module/wallpaper/gallery/edit/(:var)' => [
        'controller' => 'Admin:Gallery@editAction'
    ],

    '/%s/module/wallpaper/gallery/delete/(:var)' => [
        'controller' => 'Admin:Gallery@deleteAction'
    ],

    '/%s/module/wallpaper/gallery/save' => [
        'controller' => 'Admin:Gallery@saveAction'
    ]
];