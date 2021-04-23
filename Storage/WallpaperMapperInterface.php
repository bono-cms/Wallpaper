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
     * Clear attached companions by primary id
     * 
     * @param int $id Walloper id
     * @return boolean
     */
    public function clearCompanions($id);

    /**
     * Save companions
     * 
     * @param int $id Walloper id
     * @param array $slaves Companion ids
     * @return boolean
     */
    public function saveCompanions($id, array $slaves);

    /**
     * Fetch companion ids
     * 
     * @param int $id Walloper id
     * @return array
     */
    public function fetchCompanionIds($id);

    /**
     * Fetch companions by primary id
     * 
     * @param int $id
     * @return array
     */
    public function fetchCompanionsById($id);

    /**
     * Fetch a wallpaper by its id
     * 
     * @param string $id Page id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations);

    /**
     * Fetches a list
     * 
     * @param int $id Id to be excluded
     * @return array
     */
    public function fetchList($id);

    /**
     * Fetch all wallpapers
     * 
     * @param int $page Current page
     * @param int $limit Output limit
     * @param array $filter
     * @param boolean|string $sort
     * @return array
     */
    public function fetchAll($page = null, $limit = null, array $filter = [], $sort = false);
}
