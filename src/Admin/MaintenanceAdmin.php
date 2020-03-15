<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

final class MaintenanceAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'app/maintenance';
    protected $baseRouteName = 'app/maintenance';
    protected $securityInformation = ['test'];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}