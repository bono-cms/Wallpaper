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
use Wallpaper\Storage\WallpaperInteriorMapperInterface;

final class WallpaperInteriorMapper extends AbstractMapper implements WallpaperInteriorMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_wallpaper_interior');
    }

    /**
     * Fetch all interiors
     * 
     * @param int $wallpaperId
     * @return array
     */
    public function fetchAll($wallpaperId)
    {
        // Columns to be selected
        $columns = [
            self::column('id'),
            self::column('wallpaper_id'),
            self::column('order'),
            self::column('filename'),
            WallpaperMapper::column('sku'),
            WallpaperTranslationMapper::column('name') => 'wallpaper'
        ];

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       // Wallpaper relation
                       ->leftJoin(WallpaperMapper::getTableName(), [
                            WallpaperMapper::column('id') => self::getRawColumn('wallpaper_id')
                       ])
                       // Wallpaper relation
                       ->leftJoin(WallpaperTranslationMapper::getTableName(), [
                            WallpaperTranslationMapper::column('id') => WallpaperMapper::getRawColumn('id'),
                            WallpaperTranslationMapper::column('lang_id') => $this->getLangId()
                       ])
                       ->whereEquals('wallpaper_id', $wallpaperId)
                       ->orderBy('id')
                       ->desc();

        return $db->queryAll();
    }
}
