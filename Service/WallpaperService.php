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
use Wallpaper\Storage\WallpaperMapperInterface;

final class WallpaperService extends AbstractManager
{
    /**
     * Constants for router
     */
    const CONTROLLER = 'Wallpaper:Wallpaper@viewAction';
    const MODULE = 'Wallpaper';

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
               ->setSku($row['sku'])
               ->setName($row['name'])
               ->setDescription($row['description'])
               ->setTitle($row['title'])
               ->setMetaDescription($row['meta_description'])
               ->setKeywords($row['keywords'])
               ->setUrl($row['slug'])
               ->setChangefreq($row['changefreq'])
               ->setPriority($row['priority']);

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
        return $this->pageMapper->createSwitchUrls($id, self::MODULE, self::CONTROLLER);
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
     * Fetch all wallpapers
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->wallpaperMapper->fetchAll());
    }

    /**
     * Saves a wallpaper
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->pageMapper->savePage(self::MODULE, self::CONTROLLER, $input['page'], $input['translation']);
    }
}
