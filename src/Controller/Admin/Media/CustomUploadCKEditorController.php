<?php

namespace App\Controller\Admin\Media;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use CoopTilleuls\Bundle\CKEditorSonataMediaBundle\Controller\MediaAdminController;

class CustomUploadCKEditorController extends MediaAdminController
{    
    /**
     * uploadAction
     *
     * @return void
     */
    public function uploadAction()
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $mediaManager = $this->get('sonata.media.manager.media');

        $request = $this->getRequest();
        $provider = $request->get('provider');
        $file = $request->files->get('upload');

        if (!$request->isMethod('POST') || !$provider || null === $file) {
            throw $this->createNotFoundException();
        }

        $context = $request->get('context', $this->get('sonata.media.pool')->getDefaultContext());

        $media = $mediaManager->create();
        $media->setBinaryContent($file);

        $mediaManager->save($media, $context, $provider);
        $this->admin->createObjectSecurity($media);

        // #1 Don't resolve the response in json but remove error alert
        return new JsonResponse([
            'uploaded'  => '1',
            'fileName'  => $media->getName(),
            'url'       => $media->getWebPath()
        ]);

        // return $this->renderWithExtraParams($this->renderTemplate('upload'), [
        //     'action' => 'list',
        //     'object' => $media,
        // ]);
    }
        
    /**
     * renderTemplate
     *
     * @param  mixed $name
     * @return void
     */
    // protected function renderTemplate($name)
    // {
    //     $templates = $this->container->getParameter('coop_tilleuls_ck_editor_sonata_media.configuration.templates');

    //     if (isset($templates[$name])) {
    //         return $templates[$name];
    //     }

    //     return null;
    // }
}
