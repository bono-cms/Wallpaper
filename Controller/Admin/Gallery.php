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

final class Gallery extends AbstractController
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
        $image = $this->getModuleService('galleryService')->fetchById($id);

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
    private function createForm(VirtualEntity $image)
    {
        $title = !$image->getId() ? 'Gallery image has been added successfully' : 'Gallery image has been updated successfully';

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Wallpaper', 'Wallpaper:Admin:Wallpaper@indexAction')
                                       ->addOne($this->translator->translate('Edit the image "%s"', $image->getWallpaper()), $this->createUrl('Wallpaper:Admin:Wallpaper@editAction', [$image->getWallpaperId()]))
                                       ->addOne($title);

        return $this->view->render('gallery/form', [
            'image' => $image
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
        $this->getModuleService('galleryService')->deleteById($id);

        $this->flashBag->set('success', 'Gallery image has been deleted successfully');
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

        $interiorService = $this->getModuleService('galleryService');
        $interiorService->save($input);

        if ($input['data']['image']['id']) {
            $this->flashBag->set('success', 'Gallery image has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'Gallery image has been added successfully');
            return $interiorService->getLastId();
        }
    }
}
