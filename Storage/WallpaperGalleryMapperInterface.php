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

interface WallpaperGalleryMapperInterface
{
    /**
     * Fetches gallery image by its id
     * 
     * @param int $id Gallery image id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetch all gallery images by wallpaper id
     * 
     * @param int $wallpaperId
     * @return array
     */
    public function fetchAll($wallpaperId);
}
