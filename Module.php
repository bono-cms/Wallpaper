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
use Wallpaper\Service\InteriorService;
use Wallpaper\Service\GalleryService;

final class Module extends AbstractCmsModule
{
    /**
    * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        return [
            'galleryService' => new GalleryService($this->getMapper('\Wallpaper\Storage\MySQL\WallpaperGalleryMapper')),
            'interiorService' => new InteriorService($this->getMapper('\Wallpaper\Storage\MySQL\WallpaperInteriorMapper')),
            'wallpaperService' => new WallpaperService($this->getMapper('\Wallpaper\Storage\MySQL\WallpaperMapper'), $this->getWebPageManager())
        ];
    }
}
