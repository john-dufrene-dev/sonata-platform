<?php

namespace App\EventSubscriber\Maintenance;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Service\Cache\ConfigurationCacheBuilder;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    /**
     * config
     *
     * @var mixed
     */
    protected $config;

    /**
     * except
     *
     * @var array
     */
    protected $except = [];

    /**
     * maintenance
     *
     * @var string
     */
    protected $maintenance = 'CONF_MAINTENANCE_STATUS';

    /**
     * ipAuthorized
     *
     * @var string
     */
    protected $ipAuthorized = 'CONF_MAINTENANCE_IP_VALID';

    /**
     * urlPattern
     *
     * @var string
     */
    protected $urlPattern = '/admin';

    /**
     * templateError
     *
     * @var string
     */
    protected $templateError = 'front/pages/maintenance.html.twig';

    /**
     * __construct
     *
     * @param  mixed $config
     * @param  mixed $container
     * @return void
     */
    public function __construct(ConfigurationCacheBuilder $config, ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * onKernelRequest
     *
     * @param  mixed $event
     * @return void
     */
    public function onKernelRequest($event): void
    {
        $maintenance = $this->config->get($this->maintenance) ? $this->config->get($this->maintenance) : false;
        $currentIP = $event->getRequest()->getClientIp();
        $ips = explode(',', $this->config->get($this->ipAuthorized));

        if ($maintenance && !in_array($currentIP, $ips)) {

            // Verify if pattern match "/admin"
            if (!$this->startsWith($event->getRequest()->server->get('REQUEST_URI'), $this->urlPattern)) {
                $engine = $this->container->get('twig');
                $template = $engine->render($this->templateError);

                // We send our response with a 503 response code (service unavailable)
                $event->setResponse(new Response($template, 503));
                $event->stopPropagation();
            }
        }
    }

    /**
     * getSubscribedEvents
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 512]
        ];
    }

    /**
     * startsWith
     *
     * @param  mixed $string
     * @param  mixed $startString
     * @return void
     */
    public function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
