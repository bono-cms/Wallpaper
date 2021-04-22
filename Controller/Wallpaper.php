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
use Krystal\Stdlib\VirtualEntity;

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
        // Request variables
        $filter = $this->request->getQuery('filter', []);
        $page = $this->request->getQuery('page', 1);
        $limit = 6;
        $sort = $this->request->getQuery('sort', false);

        // Load view plugins
        $this->loadSitePlugins();

        $wallpaperService = $this->getModuleService('wallpaperService');

        $page = new VirtualEntity();
        $page->setSeo(false)
             ->setTitle($this->translator->translate('Wallpaper catalog'));

        return $this->view->render('wallpaper-catalog', [
            'languages' => $this->getService('Cms', 'languageManager')->fetchAll(true),
            'page' => $page,
            'wallpapers' => $wallpaperService->fetchAll($page, $limit, $filter, $sort),
            'paginator' => $wallpaperService->getPaginator()
        ]);
    }
}
