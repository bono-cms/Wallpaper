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

final class Interior extends AbstractController
{
    /**
     * Adds an interior
     * 
     * @param int $wallpaperId
     * @return string
     */
    public function addAction($wallpaperId)
    {
        $wallpaper = $this->getModuleService('wallpaperService')->fetchById($wallpaperId, false);

        if ($wallpaper) {
            $image = new VirtualEntity();
            $image->setWallpaperId($wallpaperId)
                  ->setWallpaper($wallpaper->getName());

            return $this->createForm($image);
        } else {
            return false;
        }
    }

    /**
     * Renders edit form
     * 
     * @param string $id Image id
     * @return string
     */
    public function editAction($id)
    {
        $image = $this->getModuleService('interiorService')->fetchById($id);

        if ($image) {
            return $this->createForm($image);
        } else {
            return false;
        }
    }

    /**
     * Creates a shared form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $interior
     * @return string
     */
    private function createForm(VirtualEntity $interior)
    {
        $title = !$interior->getId() ? 'Interior has been added successfully' : 'Interior has been updated successfully';

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Wallpaper', 'Wallpaper:Admin:Wallpaper@indexAction')
                                       ->addOne($this->translator->translate('Edit the wallpaper "%s"', $interior->getWallpaper()), $this->createUrl('Wallpaper:Admin:Wallpaper@editAction', [$interior->getWallpaperId()]))
                                       ->addOne($title);

        return $this->view->render('interior/form', [
            'interior' => $interior
        ]);
    }

    /**
     * Deletes an image
     * 
     * @param int $id 
     * @return string
     */
    public function deleteAction($id)
    {
        $this->getModuleService('interiorService')->deleteById($id);

        $this->flashBag->set('success', 'Interior has been deleted successfully');
        return 1;
    }

    /**
     * Saves an interior
     * 
     * @return string
     */
    public function saveAction()
    {
        // Get raw post data
        $input = $this->request->getAll();

        $interiorService = $this->getModuleService('interiorService');
        $interiorService->save($input);

        if ($input['data']['interior']['id']) {
            $this->flashBag->set('success', 'An interior has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'An interior has been added successfully');
            return $interiorService->getLastId();
        }
    }
}
