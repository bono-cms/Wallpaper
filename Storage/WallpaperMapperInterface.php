<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper\Storage;

interface WallpaperMapperInterface
{
    /**
     * Fetch a wallpaper by its id
     * 
     * @param string $id Page id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations);

    /**
     * Fetch all wallpapers
     * 
     * @return array
     */
    public function fetchAll();
}
