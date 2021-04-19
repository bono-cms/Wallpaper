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
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->wallpaperMapper->savePage();
    }
}
