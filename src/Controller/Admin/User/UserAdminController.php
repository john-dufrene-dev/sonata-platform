<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User\ResetPasswordRequest;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use App\Repository\User\ResetPasswordRequestRepository;
use Sonata\AdminBundle\Exception\ModelManagerException;

final class UserAdminController extends CRUDController
{    
    /**
     * registry
     *
     * @var mixed
     */
    protected $registry;
    
    /**
     * repo
     *
     * @var mixed
     */
    protected $repo;
    
    /**
     * __construct
     *
     * @param  mixed $registry
     * @param  mixed $em
     * @return void
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->registry = $registry;
        $this->em = $em;
        $this->repo = new ResetPasswordRequestRepository($this->registry, ResetPasswordRequest::class);
    }
    
    /**
     * preDelete
     *
     * @param  mixed $request
     * @param  mixed $object
     * @return void
     */
    protected function preDelete(Request $request, $object)
    {
        if ($object) {
            
            $passwords = $this->repo->findBy(['user' => $object->getId()]);

            if (!$passwords) {
                return null;
            }

            foreach($passwords as $password) {
                $this->em->remove($password);
            }

            $this->em->flush();
        }

        return null;
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

        foreach($query->getParameters() as $parameter) {
            $passwords = $this->repo->findBy(['user' => $parameter->getValue()]);

            if (!empty($passwords)) {
                
                foreach($passwords as $password) {
                    $this->em->remove($password);
                }
    
                $this->em->flush();
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
}
