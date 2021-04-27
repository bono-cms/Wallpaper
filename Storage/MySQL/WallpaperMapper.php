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
use Wallpaper\Collection\ColorCollection;
use Wallpaper\Collection\PatternCollection;
use Wallpaper\Collection\FormatCollection;
use Wallpaper\Collection\PurposeCollection;

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
     * @param boolean $withGalleryRelation
     * @return array
     */
    private function getColumns($withGalleryRelation = false)
    {
        // Columns to be selected
        $columns = [
            self::column('id'),
            self::column('sku'),
            self::column('interior_id'),
            self::column('image_id'),
            self::column('purpose'),
            self::column('pattern'),
            self::column('format'),
            self::column('group'),
            WallpaperInteriorMapper::column('filename') => 'interior',

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

        if ($withGalleryRelation) {
            $columns[WallpaperGalleryMapper::column('filename')] = 'image';
        }

        return $columns;
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
     * Fetch companions by primary id
     * 
     * @param int $id
     * @return array
     */
    public function fetchCompanionsById($id)
    {
        $ids = $this->fetchCompanionIds($id);

        // Avoid running empty query on empty result-set
        if (!empty($ids)) {
            $db = $this->createWebPageSelect($this->getColumns(true))
                       ->leftJoin(WallpaperInteriorMapper::getTableName(), [
                            WallpaperInteriorMapper::column('id') => self::getRawColumn('interior_id')
                       ])
                       ->leftJoin(WallpaperGalleryMapper::getTableName(), [
                            WallpaperGalleryMapper::column('id') => self::getRawColumn('image_id')
                       ])
                       ->whereEquals(WallpaperTranslationMapper::column('lang_id'), $this->getLangId())
                       ->andWhereIn(self::column('id'), $ids)
                       ->orderBy(self::column('id'))
                       ->desc();

            return $db->queryAll();
        } else {
            return [];
        }
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
        $db = $this->createWebPageSelect($this->getColumns(true))
                   ->leftJoin(WallpaperInteriorMapper::getTableName(), [
                        WallpaperInteriorMapper::column('id') => self::getRawColumn('interior_id')
                   ])
                   ->leftJoin(WallpaperGalleryMapper::getTableName(), [
                        WallpaperGalleryMapper::column('id') => self::getRawColumn('image_id')
                   ])
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
            self::column('sku'),
            WallpaperTranslationMapper::column('name')
        ];

        $db = $this->createWebPageSelect($columns)
                   ->whereEquals(WallpaperTranslationMapper::column('lang_id'), $this->getLangId());

        // Exclude, if required
        if ($id != null) {
            $db->andWhereNotEquals(self::column('id'), $id);
        } else {
            $db->orderBy(self::column('id'))
               ->desc();
        }

        return $db->queryAll();
    }

    /**
     * Fetch all wallpapers
     * 
     * @param int $page Current page
     * @param int $limit Output limit
     * @param array $filter
     * @param boolean|string $sort
     * @return array
     */
    public function fetchAll($page = null, $limit = null, array $filter = [], $sort = false)
    {
        // Whether color filter is active
        $hasColor = isset($filter['colors']) && (new ColorCollection)->hasKeys($filter['colors']);

        $db = $this->createWebPageSelect($this->getColumns(false))
                   ->leftJoin(WallpaperInteriorMapper::getTableName(), [
                        WallpaperInteriorMapper::column('id') => self::getRawColumn('interior_id')
                   ])
                   ->leftJoin(WallpaperGalleryMapper::getTableName(), [
                        WallpaperGalleryMapper::column('wallpaper_id') => self::getRawColumn('id')
                   ])
                   ->whereEquals(WallpaperTranslationMapper::column('lang_id'), $this->getLangId());

        // Color filter
        if ($hasColor) {
            $db->andWhereIn(WallpaperGalleryMapper::column('color'), $filter['colors']);
        }

        // Purposes filter
        if (isset($filter['purposes']) && (new PurposeCollection)->hasKeys($filter['purposes'])) {
            $db->andWhereIn(self::column('purpose'), $filter['purposes']);
        }

        // Patterns filter
        if (isset($filter['patterns']) && (new PatternCollection)->hasKeys($filter['patterns'])) {
            $db->andWhereIn(self::column('pattern'), $filter['patterns']);
        }

        // Formats filter
        if (isset($filter['formats']) && (new FormatCollection)->hasKeys($filter['formats'])) {
            $db->andWhereIn(self::column('format'), $filter['formats']);
        }

        switch ($sort) {
            case false:
                $db->orderBy(self::column('id'))
                   ->desc();
            break;

            case 'sku':
                $db->orderBy(self::column('sky'));
            break;
        }

        // Limit
        if ($page === null && $limit !== null) {
            $db->limit($limit);
        }

        // Apply pagination
        if ($page !== null && $limit !== null){
            $db->paginate($page, $limit);
        }

        //d($filter['colors']);
        return $db->queryAll();
    }
}
