<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper;

use Cms\AbstractCmsModule;
use Wallpaper\Service\WallpaperService;

final class Module extends AbstractCmsModule
{
    /**
    * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        return [
            'wallpaperService' => new WallpaperService($this->getMapper('\Wallpaper\Storage\MySQL\WallpaperMapper'), $this->getWebPageManager())
        ];
    }
}