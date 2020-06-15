<?php

declare(strict_types=1);

namespace App\Controller\Admin\Security;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class AdminUserController extends CRUDController
{
    /**
     * setContainer
     *
     * @param  mixed $container
     * @return void
     */
    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;

        $this->configure();

        if ($this->admin->isCurrentRoute('edit')) {

            $request = $this->getRequest();
            $id = $request->get($this->admin->getIdParameter());

            if (
                !$this->isGranted('ROLE_SUPER_ADMIN')
                && !$this->isGranted('ROLE_NUCLEOS_USER_ADMIN_ADMIN_USER_ALL')
            ) {
                $currentUserEdit = (!$this->getUser() || $id == $this->getUser()->getId()) ? true : false;

                if (!$currentUserEdit) {
                    throw $this->createNotFoundException(sprintf('You can\'t edit this id : %s', $id));
                }
            }
        }
    }
}
