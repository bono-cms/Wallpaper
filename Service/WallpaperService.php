<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper\Service;

use Cms\Service\WebPageManagerInterface;
use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Wallpaper\Storage\WallpaperMapperInterface;

final class WallpaperService extends AbstractManager
{
    /**
     * Module constants
     */
    const CONTROLLER = 'Wallpaper:Wallpaper@viewAction';
    const MODULE = 'Wallpaper';
    const DIR_INTERIOR = 'data/uploads/module/wallpaper/interior';
    const DIR_GALLERY = 'data/uploads/module/wallpaper/gallery';

    /**
     * Any compliant wallpaper mapper
     * 
     * @var \Wallpaper\Storage\WallpaperMapperInterface
     */
    private $wallpaperMapper;

    /**
     * Web page manager is responsible for managing slugs
     * 
     * @var \Cms\Service\WebPageManagerInterface
     */
    private $webPageManager;

    /**
     * State initialization
     * 
     * @param \Wallpaper\Storage\WallpaperMapperInterface $wallpaperMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @return void
     */
    public function __construct(WallpaperMapperInterface $wallpaperMapper, WebPageManagerInterface $webPageManager)
    {
        $this->wallpaperMapper = $wallpaperMapper;
        $this->webPageManager = $webPageManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'])
               ->setLangId($row['lang_id'])
               ->setWebPageId($row['web_page_id'])
               ->setInteriorId($row['interior_id'])
               ->setImageId($row['image_id'])
               ->setImage($row['image'])
               ->setInterior($row['interior'])
               ->setSku($row['sku'])
               ->setName($row['name'])
               ->setDescription($row['description'])
               ->setTitle($row['title'])
               ->setMetaDescription($row['meta_description'])
               ->setKeywords($row['keywords'])
               ->setSlug($row['slug'])
               ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
               ->setChangefreq($row['changefreq'])
               ->setPriority($row['priority'])
               ->setPurpose($row['purpose'])
               ->setFormat($row['format'])
               ->setPattern($row['pattern']);

        return $entity;
    }

    /**
     * Returns a collection of switching URLs
     * 
     * @param string $id Page ID
     * @return array
     */
    public function getSwitchUrls($id)
    {
        return $this->wallpaperMapper->createSwitchUrls($id, self::MODULE, self::CONTROLLER);
    }

    /**
     * Returns prepared pagination instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->wallpaperMapper->getPaginator();
    }

    /**
     * Returns last wallpaper id
     * 
     * @return string
     */
    public function getLastId()
    {
        return $this->wallpaperMapper->getMaxId();
    }

    /**
     * Deletes a wallpaper by its id
     * 
     * @param int $id Wallpaper id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->wallpaperMapper->deletePage($id);
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
        return $this->wallpaperMapper->fetchCompanionIds($id);
    }

    /**
     * Fetches a wallpaper by its id
     * 
     * @param int $id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations) {
            return $this->prepareResults($this->wallpaperMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->wallpaperMapper->fetchById($id, false));
        }
    }

    /**
     * Fetches a list
     * 
     * @param int $id
     * @return array
     */
    public function fetchList($id)
    {
        return ArrayUtils::arrayList($this->wallpaperMapper->fetchList($id), 'id', 'sku');
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
        return $this->prepareResults($this->wallpaperMapper->fetchAll($page, $limit, $filter, $sort));
    }

    /**
     * Saves a wallpaper
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function save(array $input)
    {
        // Do save first
        $this->wallpaperMapper->savePage(self::MODULE, self::CONTROLLER, $input['wallpaper'], $input['translation']);

        if ($input['wallpaper']['id']) {
            $id = $input['wallpaper']['id'];
        } else {
            $id = $this->getLastId();
        }

        // Do we have on attached companion?
        if (isset($input['companions'])) {
            return $this->wallpaperMapper->saveCompanions($id, $input['companions']);
        } else {
            return $this->wallpaperMapper->clearCompanions($id);
        }
    }
}
