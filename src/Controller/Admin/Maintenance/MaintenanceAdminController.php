<?php

namespace App\Controller\Admin\Maintenance;

use Sonata\AdminBundle\Controller\CRUDController;

class MaintenanceAdminController extends CRUDController
{
    protected $template = 'admin/pages/maintenance/maintenance_view.html.twig';

    protected $options = ['CONF_MAINTENANCE_STATUS', 'CONF_MAINTENANCE_IP_VALID'];

    public function listAction()
    {
        $this->admin->checkAccess('list');
        
        return $this->renderWithExtraParams($this->template, [
            'action' => 'Mode maintenance',
            'options' => $this->options,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'currentIP' => $this->admin->getRequest()->getClientIp(),
        ], null);
    }
}
