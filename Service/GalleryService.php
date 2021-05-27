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

use Krystal\Stdlib\VirtualEntity;
use Cms\Service\AbstractManager;
use Wallpaper\Storage\WallpaperGalleryMapperInterface;
use Wallpaper\Collection\ColorCollection;

final class GalleryService extends AbstractManager
{
    /**
     * Any compliant gallery mapper
     * 
     * @var \Wallpaper\Storage\WallpaperGalleryMapperInterface
     */
    private $wallpaperGalleryMapper;

    /**
     * State initialization
     * 
     * @param \Wallpaper\Storage\WallpaperGalleryMapperInterface $wallpaperGalleryMapper
     * @return void
     */
    public function __construct(WallpaperGalleryMapperInterface $wallpaperGalleryMapper)
    {
        $this->wallpaperGalleryMapper = $wallpaperGalleryMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $colorCol = new ColorCollection();

        $entity = new VirtualEntity();
        $entity->setId($row['id'])
               ->setWallpaperId($row['wallpaper_id'])
               ->setSku($row['sku'])
               ->setOrder($row['order'])
               ->setColor($row['color'])
               ->setColorName($colorCol->findByKey($row['color']))
               ->setFilename($row['filename']);

        if (isset($row['wallpaper'])) {
            $entity->setWallpaper($row['wallpaper']);
        }

        return $entity;
    }

    /**
     * Returns last id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->wallpaperGalleryMapper->getMaxId();
    }

    /**
     * Deletes an image by its id
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->wallpaperGalleryMapper->deleteByPk($id);
    }

    /**
     * Fetches an image by its id
     * 
     * @param int $id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->wallpaperGalleryMapper->fetchById($id));
    }

    /**
     * Fetch all images
     * 
     * @param int $wallpaperId
     * @return array
     */
    public function fetchAll($wallpaperId)
    {
        return $this->prepareResults($this->wallpaperGalleryMapper->fetchAll($wallpaperId));
    }

    /**
     * Append images
     * 
     * @param array $wallpapers
     * @return void
     */
    public function appendImages(array $wallpapers)
    {
        foreach ($wallpapers as $wallpaper) {
            $wallpaper->setImages($this->fetchAll($wallpaper->getId()));
        }
    }

    /**
     * Saves gallery image
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        // Upload file, if provided
        $this->appendFileData($input, 'image', 'filename', WallpaperService::DIR_GALLERY);

        return $this->wallpaperGalleryMapper->persist($input['data']['image']);
    }
}
