<?php

declare(strict_types=1);

namespace App\Controller\Admin\Redirect;

use App\Entity\Redirect\Redirect;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Cache\RedirectCacheBuilder;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;

final class RedirectAdminController extends CRUDController
{        
    /**
     * em
     *
     * @var mixed
     */
    protected $em;

    /**
     * cacheredirect
     *
     * @var mixed
     */
    protected $cacheredirect;
    
    /**
     * __construct
     *
     * @param  mixed $cacheredirect
     * @return void
     */
    public function __construct(EntityManagerInterface $em, RedirectCacheBuilder $cacheredirect)
    {
        $this->em = $em;
        $this->cacheredirect = $cacheredirect;
    }

    /**
     * batchActionDelete
     *
     * @param  mixed $query
     * @return void
     */
    public function batchActionDelete(ProxyQueryInterface $query)
    {
        $this->admin->checkAccess('batchDelete');

        $modelManager = $this->admin->getModelManager();

        foreach($query->getQuery()->getResult() as $parameter) {
            if (!empty($parameter)) {
                $this->cacheredirect->remove($parameter->getSource());
            } 
        }

        try {
            $modelManager->batchDelete($this->admin->getClass(), $query);
            $this->addFlash(
                'sonata_flash_success',
                $this->trans('flash_batch_delete_success', [], 'SonataAdminBundle')
            );
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash(
                'sonata_flash_error',
                $this->trans('flash_batch_delete_error', [], 'SonataAdminBundle')
            );
        }

        return $this->redirectToList();
    }

    protected function preList(Request $request)
    {
        $redirects = $this->em->getRepository(Redirect::class)->findAll();

        // Update all redirects cache on ListAction
        foreach($redirects as $r) {
            $this->cacheredirect->update( $r->getSource(), $r->getDestination(), $r->getHttpCode());
        }
    }
}
