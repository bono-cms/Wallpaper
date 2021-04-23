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

final class PurposeCollection extends ArrayCollection
{
    /**
     * {@inheritDoc}
     */
    protected $collection = [
        1 => 'Living Room',
        2 => 'Bedroom',
        3 => 'Hallway',
        4 => 'Kitchen',
        5 => 'Playroom'
    ];
}
