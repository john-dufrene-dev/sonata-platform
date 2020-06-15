<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Media;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class CustomMediaAdminController extends CRUDController
{
    /**
     * {@inheritdoc}
     */
    public function createAction()
    {
        $request = $this->getRequest();
        
        $this->admin->checkAccess('create');

        if (!$request->get('provider') && $request->isMethod('get')) {
            $pool = $this->get('sonata.media.pool');

            return $this->render('@SonataMedia/MediaAdmin/select_provider.html.twig', [
                'providers' => $pool->getProvidersByContext(
                    $request->get('context', $pool->getDefaultContext())
                ),
                'action' => 'create',
            ]);
        }

        return parent::createAction();
    }

    /**
     * {@inheritdoc}
     */
    public function listAction()
    {
        $request = $this->getRequest();

        $this->admin->checkAccess('list');

        if ($listMode = $request->get('_list_mode', 'mosaic')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();

        $filters = $request->get('filter');

        // set the default context
        if (!$filters || !\array_key_exists('context', $filters)) {
            $context = $this->admin->getPersistentParameter('context', $this->get('sonata.media.pool')->getDefaultContext());
        } else {
            $context = $filters['context']['value'];
        }

        $datagrid->setValue('context', null, $context);

        $rootCategory = null;
        if ($this->has('sonata.media.manager.category')) {
            $rootCategory = $this->get('sonata.media.manager.category')->getRootCategory($context);
        }

        if (null !== $rootCategory && !$filters) {
            $datagrid->setValue('category', null, $rootCategory->getId());
        }
        if ($this->has('sonata.media.manager.category') && $request->get('category')) {
            $category = $this->get('sonata.media.manager.category')->findOneBy([
                'id' => (int) $request->get('category'),
                'context' => $context,
            ]);

            if (!empty($category)) {
                $datagrid->setValue('category', null, $category->getId());
            } else {
                $datagrid->setValue('category', null, $rootCategory->getId());
            }
        }

        $formView = $datagrid->getForm()->createView();

        $this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), [
            'action' => 'list',
            'form' => $formView,
            'datagrid' => $datagrid,
            'root_category' => $rootCategory,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function render($view, array $parameters = [], ?Response $response = null)
    {
        $parameters['media_pool'] = $this->get('sonata.media.pool');
        $parameters['persistent_parameters'] = $this->admin->getPersistentParameters();

        return parent::renderWithExtraParams($view, $parameters, $response);
    }

    /**
     * @throws AccessDeniedException
     */
    public function browserAction(): Response
    {
        $request = $this->getRequest();

        $this->checkIfMediaBundleIsLoaded();

        $this->admin->checkAccess('list');

        $datagrid = $this->admin->getDatagrid();

        $filters = $request->get('filter');

        // set the default context
        if (!$filters || !\array_key_exists('context', $filters)) {
            $context = $this->admin->getPersistentParameter('context', $this->get('sonata.media.pool')->getDefaultContext());
        } else {
            $context = $filters['context']['value'];
        }

        $datagrid->setValue('context', null, $context);
        $datagrid->setValue('providerName', null, $this->admin->getPersistentParameter('provider'));

        $rootCategory = null;
        if ($this->has('sonata.media.manager.category')) {
            $rootCategory = $this->get('sonata.media.manager.category')->getRootCategory($context);
        }

        if (null !== $rootCategory && !$filters) {
            $datagrid->setValue('category', null, $rootCategory->getId());
        }
        if ($this->has('sonata.media.manager.category') && $request->get('category')) {
            $category = $this->get('sonata.media.manager.category')->findOneBy([
                'id' => (int) $request->get('category'),
                'context' => $context,
            ]);

            if (!empty($category)) {
                $datagrid->setValue('category', null, $category->getId());
            } else {
                $datagrid->setValue('category', null, $rootCategory->getId());
            }
        }

        $formats = [];
        foreach ($datagrid->getResults() as $media) {
            $formats[$media->getId()] = $this->get('sonata.media.pool')->getFormatNamesByContext($media->getContext());
        }

        $formView = $datagrid->getForm()->createView();

        $this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->renderWithExtraParams($this->getTemplate('browser'), [
            'action' => 'browser',
            'form' => $formView,
            'datagrid' => $datagrid,
            'root_category' => $rootCategory,
            'formats' => $formats,
        ]);
    }

    /**
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function uploadAction(): Response
    {
        $request = $this->getRequest();
        
        $this->checkIfMediaBundleIsLoaded();

        $this->admin->checkAccess('create');

        $mediaManager = $this->get('sonata.media.manager.media');

        $provider = $request->get('provider');
        $file = $request->files->get('upload');

        if (!$request->isMethod('POST') || !$provider || null === $file) {
            throw $this->createNotFoundException();
        }

        $pool = $this->get('sonata.media.pool');
        $context = $request->get('context', $pool->getDefaultContext());

        $media = $mediaManager->create();
        $media->setBinaryContent($file);

        $mediaManager->save($media, $context, $provider);
        $this->admin->createObjectSecurity($media);

        $format = $pool->getProvider($provider)->getFormatName(
            $media,
            $request->get('format', MediaProviderInterface::FORMAT_REFERENCE)
        );

        // #1 Don't resolve the response in json but remove error alert
        return new JsonResponse([
            'uploaded'  => '1',
            'fileName'  => $media->getName(),
            'url'       => $media->getWebPath()
        ]);

        // return $this->renderWithExtraParams($this->getTemplate('upload'), [
        //     'action' => 'list',
        //     'object' => $media,
        //     'format' => $format,
        // ]);
    }

    private function getTemplate(string $name): string
    {
        $templates = [
            'browser'   => 'admin/pages/media/Ckeditor/browser.html.twig',
            'upload'    => 'admin/pages/media/Ckeditor/upload.html.twig',
        ];

        if (isset($templates[$name])) {
            return $templates[$name];
        }

        throw new \LogicException(sprintf(
            'Template with name "%s" does not exist',
            $name
        ));
    }

    /**
     * Checks if SonataMediaBundle is loaded otherwise throws an exception.
     *
     * @throws \RuntimeException
     */
    private function checkIfMediaBundleIsLoaded(): void
    {
        $bundles = $this->container->getParameter('kernel.bundles');

        if (!isset($bundles['SonataMediaBundle'])) {
            throw new \RuntimeException('You cannot use this feature because you have to use SonataMediaBundle');
        }
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView, array $theme): void
    {
        $this->get('twig')->getRuntime(FormRenderer::class)->setTheme($formView, $theme);
    }
}