<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper\Collection;

use Krystal\Stdlib\ArrayCollection;

final class ColorCollection extends ArrayCollection
{
    /* Color constants */
    const WHITE = 1;
    const BLACK = 2;
    const BLUE = 3;
    const RED = 4;
    const YELLOW = 5;
    const BROWN = 6;
    const PURPLE = 7;

    /**
     * {@inheritDoc}
     */
    protected $collection = [
        self::WHITE => 'White',
        self::BLACK => 'Black',
        self::BLUE => 'Blue',
        self::RED => 'Red',
        self::YELLOW => 'Yellow',
        self::BROWN => 'Brown',
        self::PURPLE => 'Purple'
    ];
}
