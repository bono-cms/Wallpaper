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

use Cms\Storage\MySQL\WebPageMapper;
use Cms\Storage\MySQL\AbstractMapper;
use Wallpaper\Storage\WallpaperMapperInterface;

final class WallpaperMapper extends AbstractMapper implements WallpaperMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_wallpaper');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return WallpaperTranslationMapper::getTableName();
    }

    /**
     * Returns a set of shared columns to be used in SELECT query
     * 
     * @return array
     */
    private function getColumns()
    {
        return [
            self::column('id'),
            self::column('sku'),

            // Translations
            WallpaperTranslationMapper::column('lang_id'),
            WallpaperTranslationMapper::column('web_page_id'),
            WallpaperTranslationMapper::column('title'),
            WallpaperTranslationMapper::column('name'),
            WallpaperTranslationMapper::column('description'),
            WallpaperTranslationMapper::column('keywords'),
            WallpaperTranslationMapper::column('meta_description'),

            // Web page meta columns
            WebPageMapper::column('slug'),
            WebPageMapper::column('changefreq'),
            WebPageMapper::column('priority')
        ];
    }

    /**
     * Fetch a wallpaper by its id
     * 
     * @param string $id Page id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findWebPage($this->getColumns(), $id, $withTranslations);
    }

    /**
     * Fetches a list
     * 
     * @param int $id Id to be excluded
     * @return array
     */
    public function fetchList($id)
    {
        // Columns to be selected
        $columns = [
            self::column('id'),
            self::column('sku')
        ];

        $db = $this->createWebPageSelect($columns)
                   ->whereEquals(WallpaperTranslationMapper::column('lang_id'), $this->getLangId())
                   ->andWhereNotEquals(self::column('id'), $id)
                   ->orderBy(self::column('id'))
                   ->desc();

        return $db->queryAll();
    }

    /**
     * Fetch all wallpapers
     * 
     * @return array
     */
    public function fetchAll()
    {
        $db = $this->createWebPageSelect($this->getColumns())
                   ->whereEquals(WallpaperTranslationMapper::column('lang_id'), $this->getLangId())
                   ->orderBy(self::column('id'))
                   ->desc();

        return $db->queryAll();
    }
}
