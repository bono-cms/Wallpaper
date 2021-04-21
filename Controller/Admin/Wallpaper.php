<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Stdlib\VirtualEntity;

final class Wallpaper extends AbstractController
{
    /**
     * Renders all wallpapers
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Wallpaper');

        return $this->view->render('wallpaper/index', [
            'wallpapers' => $this->getModuleService('wallpaperService')->fetchAll()
        ]);
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $wallpaper
     * @param string $title Page title
     * @return string
     */
    private function createForm($wallpaper, $title)
    {
        $wallpaperService = $this->getModuleService('wallpaperService');
        $isNew = !is_array($wallpaper);
        $id = $isNew ? $wallpaper->getId() : $wallpaper[0]['id'];

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Wallpaper', 'Wallpaper:Admin:Wallpaper@indexAction')
                                       ->addOne($isNew ? 'Add new wallpaper' : 'Update wallpaper');

        return $this->view->render('wallpaper/form', [
            'wallpaper' => $wallpaper,
            'companions' => $wallpaperService->fetchList($id),
            'companionIds' => $isNew ? [] : $wallpaperService->fetchCompanionIds($id),
            'interiors' => $isNew ? [] : $this->getModuleService('interiorService')->fetchAll($id),
            'images' => $isNew ? [] : $this->getModuleService('galleryService')->fetchAll($id),
            'isNew' => $isNew,
            'id' => $id
        ]);
    }

    /**
     * Renders adding form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity(), 'Add new wallpaper');
    }

    /**
     * Renders edit form
     * 
     * @param string $id Wallpaper id
     * @return string
     */
    public function editAction($id)
    {
        $wallpaper = $this->getModuleService('wallpaperService')->fetchById($id, true);

        if ($wallpaper) {
            return $this->createForm($wallpaper, 'Edit the wallpaper');
        } else {
            return false;
        }
    }

    /**
     * Deletes a wallpaper
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteAction($id)
    {
        $this->getModuleService('wallpaperService')->deleteById($id);

        $this->flashBag->set('success', 'A wallpaper has been deleted successfully');
        return 1;
    }

    /**
     * Saves a wallpaper
     * 
     * @return boolean
     */
    public function saveAction()
    {
        // Get raw input data
        $input = $this->request->getPost();

        $wallpaperService = $this->getModuleService('wallpaperService');
        $wallpaperService->save($input);

        if ($input['wallpaper']['id']) {
            $this->flashBag->set('success', 'Wallpaper has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'Wallpaper has been added successfully');
            return $wallpaperService->getLastId();
        }
    }
}
