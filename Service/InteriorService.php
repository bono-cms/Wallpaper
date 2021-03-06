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

use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;
use Wallpaper\Storage\WallpaperInteriorMapperInterface;

final class InteriorService extends AbstractManager
{
    /**
     * Any-compliant interior mapper
     * 
     * @var \Wallpaper\Storage\WallpaperInteriorMapperInterface
     */
    private $wallpaperInteriorMapper;

    /**
     * State initialization
     * 
     * @param \Wallpaper\Storage\WallpaperInteriorMapperInterface $wallpaperInteriorMapper
     * @return void
     */
    public function __construct(WallpaperInteriorMapperInterface $wallpaperInteriorMapper)
    {
        $this->wallpaperInteriorMapper = $wallpaperInteriorMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'])
               ->setWallpaperId($row['wallpaper_id'])
               ->setOrder($row['order'])
               ->setFilename($row['filename']);

        if (isset($row['wallpaper'])){
            $entity->setWallpaper($row['wallpaper']);
        }

        return $entity;
    }

    /**
     * Returns last interior id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->wallpaperInteriorMapper->getMaxId();
    }

    /**
     * Deletes interior image by its id
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->wallpaperInteriorMapper->deleteByPk($id);
    }

    /**
     * Saves an interior
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        // Upload file, if provided
        $this->appendFileData($input, 'interior', 'filename', WallpaperService::DIR_INTERIOR);

        return $this->wallpaperInteriorMapper->persist($input['data']['interior']);
    }

    /**
     * Fetch interior by id
     * 
     * @return array
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->wallpaperInteriorMapper->fetchById($id));
    }

    /**
     * Fetch all wallpapers
     * 
     * @param int $wallpaperId
     * @return array
     */
    public function fetchAll($wallpaperId)
    {
        return $this->prepareResults($this->wallpaperInteriorMapper->fetchAll($wallpaperId));
    }
}
