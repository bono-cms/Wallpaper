<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Wallpaper\Storage\WallpaperGalleryMapperInterface;

final class WallpaperGalleryMapper extends AbstractMapper implements WallpaperGalleryMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_wallpaper_gallery');
    }

    /**
     * Fetch all gallery images by wallpaper id
     * 
     * @param int $wallpaperId
     * @return array
     */
    public function fetchAll($wallpaperId)
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->whereEquals('wallpaper_id', $wallpaperId)
                       ->orderBy('id')
                       ->desc();

        return $db->queryAll();
    }
}
