<?php

declare(strict_types=1);

namespace App\Admin\Maintenance;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

final class MaintenanceAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'app/maintenance';
    protected $baseRouteName = 'app/maintenance';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}