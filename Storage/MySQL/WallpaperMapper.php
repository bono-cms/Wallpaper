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
            self::column('interior_id'),
            self::column('image_id'),
            WallpaperInteriorMapper::column('filename') => 'interior',
            WallpaperGalleryMapper::column('filename') => 'image',

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
     * Clear attached companions by primary id
     * 
     * @param int $id Walloper id
     * @return boolean
     */
    public function clearCompanions($id)
    {
        return $this->removeFromJunction(WallpaperCompanionMapper::getTableName(), $id);
    }

    /**
     * Save companions
     * 
     * @param int $id Walloper id
     * @param array $slaves Companion ids
     * @return boolean
     */
    public function saveCompanions($id, array $slaves)
    {
        return $this->syncWithJunction(WallpaperCompanionMapper::getTableName(), $id, $slaves);
    }

    /**
     * Fetch companion ids
     * 
     * @param int $id Walloper id
     * @return array
     */
    public function fetchCompanionIds($id)
    {
        return $this->getSlaveIdsFromJunction(WallpaperCompanionMapper::getTableName(), $id);
    }

    /**
     * Create shared select instance
     * 
     * @return \Krystal\Db\Sql\Db
     */
    private function createSharedSelect()
    {
        $db = $this->createWebPageSelect($this->getColumns())
                   ->leftJoin(WallpaperInteriorMapper::getTableName(), [
                        WallpaperInteriorMapper::column('id') => self::getRawColumn('interior_id')
                   ])
                   ->leftJoin(WallpaperGalleryMapper::getTableName(), [
                        WallpaperGalleryMapper::column('id') => self::getRawColumn('image_id')
                   ]);

        return $db;
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
        $db = $this->createSharedSelect()
                   ->whereEquals(self::column('id'), $id);

        if ($withTranslations === true) {
            return $db->queryAll();
        } else {
            return $db->andWhereEquals(self::column(self::PARAM_COLUMN_LANG_ID, self::getTranslationTable()), $this->getLangId())
                      ->query();
        }
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
        $db = $this->createSharedSelect()
                   ->whereEquals(WallpaperTranslationMapper::column('lang_id'), $this->getLangId())
                   ->orderBy(self::column('id'))
                   ->desc();

        return $db->queryAll();
    }
}
