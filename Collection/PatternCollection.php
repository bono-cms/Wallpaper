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

final class PatternCollection extends ArrayCollection
{
    /**
     * {@inheritDoc}
     */
    protected $collection = [
        1 => 'Classic',
        2 => 'Floristics',
        3 => 'Ornament',
        4 => 'Geometry',
        5 => 'Thematic',
        6 => 'Child',
        7 => 'Nature',
        8 => 'Background'
    ];
}
