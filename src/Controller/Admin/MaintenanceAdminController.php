<?php

namespace App\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;

class MaintenanceAdminController extends CRUDController
{
    protected $template = 'admin/maintenance/maintenance_view.html.twig';

    protected $options = ['CONF_MAINTENANCE_IP_VALID', 'CONF_MAINTENANCE_STATUS'];

    public function listAction()
    {
        $this->admin->checkAccess('list');
        
        return $this->renderWithExtraParams($this->template, [
            'action' => 'Mode maintenance',
            'options' => $this->options,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ], null);
    }
}
