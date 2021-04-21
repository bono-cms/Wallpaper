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
     * Creates shared query
     * 
     * @return \Krystal\Db\Sql\Db
     */
    private function createSharedQuery()
    {
        // Columns to be selected
        $columns = [
            self::column('id'),
            self::column('wallpaper_id'),
            self::column('order'),
            self::column('color'),
            self::column('filename'),
            self::column('sku'),
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
                       ]);

        return $db;
    }

    /**
     * Fetches gallery image by its id
     * 
     * @param int $id Gallery image id
     * @return array
     */
    public function fetchById($id)
    {
        $db = $this->createSharedQuery()
                   ->whereEquals(self::column('id'), $id);

        return $db->query();
    }

    /**
     * Fetch all gallery images by wallpaper id
     * 
     * @param int $wallpaperId
     * @return array
     */
    public function fetchAll($wallpaperId)
    {
        $db = $this->createSharedQuery()
                   ->whereEquals('wallpaper_id', $wallpaperId)
                   ->orderBy('id')
                   ->desc();

        return $db->queryAll();
    }
}
