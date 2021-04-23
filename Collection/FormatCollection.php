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

final class FormatCollection extends ArrayCollection
{
    /**
     * {@inheritDoc}
     */
    protected $collection = [
        1 => '0,53 x 10',
        2 => '1,06 x 10',
    ];
}
