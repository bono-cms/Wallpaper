<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper\Controller;

use Site\Controller\AbstractController;

final class Wallpaper extends AbstractController
{
    /**
     * Renders single wallpaper
     * 
     * @param int $id Wallpaper id
     * @return string
     */
    public function viewAction($id)
    {
        $wallpaper = $this->getModuleService('wallpaperService')->fetchById($id, false);

        if ($wallpaper) {
            $wallpaper->setGallery($this->getModuleService('galleryService')->fetchAll($id));

            // ...
        } else {
            return false;
        }
    }

    /**
     * Renders filter page
     * 
     * @return string
     */
    public function filterAction()
    {
        $filter = $this->request->getQuery('filter', []);

        // ...
    }
}
