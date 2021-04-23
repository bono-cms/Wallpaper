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

use Krystal\Stdlib\VirtualEntity;
use Site\Controller\AbstractController;
use Wallpaper\Collection\PatternCollection;
use Wallpaper\Collection\FormatCollection;
use Wallpaper\Collection\PurposeCollection;
use Wallpaper\Collection\ColorCollection;

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
        $wallpaperService = $this->getModuleService('wallpaperService');
        $wallpaper = $wallpaperService->fetchById($id, false);

        if ($wallpaper) {
            $wallpaper->setGallery($this->getModuleService('galleryService')->fetchAll($id))
                      ->setInteriors($this->getModuleService('interiorService')->fetchAll($id));

            $this->loadSitePlugins();

            return $this->view->render('wallpaper-single', [
                'wallpaper' => $wallpaper,
                'page' => $wallpaper,
                'languages' => $wallpaperService->getSwitchUrls($id)
            ]);

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
        $wallpapers = $wallpaperService->fetchAll($page, $limit, $filter, $sort);

        // Append images
        $this->getModuleService('galleryService')->appendImages($wallpapers);

        $page = new VirtualEntity();
        $page->setSeo(false)
             ->setTitle($this->translator->translate('Wallpaper catalog'));

        return $this->view->render('wallpaper-catalog', [
            'filter' => [
                'active' => $filter,
                'colors' => (new ColorCollection)->getAll(),
                'purposes' => (new PurposeCollection)->getAll(),
                'formats' => (new FormatCollection)->getAll(),
                'patterns' => (new PatternCollection)->getAll()
            ],
            'languages' => $this->getService('Cms', 'languageManager')->fetchAll(true),
            'page' => $page,
            'wallpapers' => $wallpapers,
            'paginator' => $wallpaperService->getPaginator()
        ]);
    }
}
