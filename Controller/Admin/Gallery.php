<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Wallpaper\Controller\Admin;

use Wallpaper\Collection\ColorCollection;
use Cms\Controller\Admin\AbstractController;
use Krystal\Stdlib\VirtualEntity;

final class Gallery extends AbstractController
{
    /**
     * Adds a gallery image
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
     * @param \Krystal\Stdlib\VirtualEntity $image
     * @return string
     */
    private function createForm(VirtualEntity $image)
    {
        $title = $image->getId() ? 'Edit gallery image' : 'Upload new gallery image';

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Wallpapers', 'Wallpaper:Admin:Wallpaper@indexAction')
                                       ->addOne($this->translator->translate('Edit the wallpaper "%s"', $image->getWallpaper()), $this->createUrl('Wallpaper:Admin:Wallpaper@editAction', [$image->getWallpaperId()]))
                                       ->addOne($title);

        return $this->view->render('gallery/form', [
            'image' => $image,
            'colors' => $this->translator->translateArray((new ColorCollection)->getAll())
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

        return $this->json([
            'refresh' => true
        ]);
    }

    /**
     * Saves a gallery image
     * 
     * @return string
     */
    public function saveAction()
    {
        $validator = $this->createValidation();

        $validator->field('image.order')
                  ->addRule('numeric');

        $validator->field('image.color')
                  ->required();

        $validator->field('image.sku')
                  ->required();

        if ($validator->isPassed()) {
            // Get raw post data
            $input = $this->request->getAll();

            $interiorService = $this->getModuleService('galleryService');
            $interiorService->save($input);

            if ($input['data']['image']['id']) {
                $this->flashBag->set('success', 'Gallery image has been updated successfully');

                return $this->json([
                    'refresh' => true
                ]);
            } else {
                $this->flashBag->set('success', 'Gallery image has been added successfully');

                return $this->json([
                    'redirect' => $this->createUrl('Wallpaper:Admin:Gallery@editAction', [$interiorService->getLastId()]),
                ]);
            }

        } else {
            return $this->json([
                'errors' => $validator->getErrors()
            ]);
        }
    }
}
