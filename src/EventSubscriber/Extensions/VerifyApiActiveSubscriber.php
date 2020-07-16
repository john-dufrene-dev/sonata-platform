<?php

namespace App\EventSubscriber\Extensions;

use Symfony\Component\HttpKernel\KernelEvents;
use App\Service\Cache\ConfigurationCacheBuilder;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifyApiActiveSubscriber implements EventSubscriberInterface
{
    /**
     * extension_account_active
     *
     * @var string
     */
    protected $extension_account_active = 'CONF_EXTENSION_ACCOUNT_ACTIVE';

    /**
     * extension_account_active
     *
     * @var string
     */
    protected $extension_api_active = 'CONF_EXTENSION_API_ACTIVE';

    /**
     * __construct
     *
     * @param  mixed $config
     * @return void
     */
    public function __construct(ConfigurationCacheBuilder $config)
    {
        $this->config = $config;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        

        if (strpos($request->getPathInfo(), 'api')) {
            
            $active_account = $this->config->get($this->extension_account_active) ? true : false;
            $active_api = $this->config->get($this->extension_api_active) ? true : false;

            if(!$active_account || !$active_api) {
                throw new NotFoundHttpException('Extension Account or Api is not active !');
            }
        }

        return;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 31]
        ];
    }
}
