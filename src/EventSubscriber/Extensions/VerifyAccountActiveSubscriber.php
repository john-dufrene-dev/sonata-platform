<?php

namespace App\EventSubscriber\Extensions;

use Symfony\Component\HttpKernel\KernelEvents;
use App\Service\Cache\ConfigurationCacheBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifyAccountActiveSubscriber implements EventSubscriberInterface
{
    /**
     * extension_account_active
     *
     * @var string
     */
    protected $extension_account_active = 'CONF_EXTENSION_ACCOUNT_ACTIVE';

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

    public function onKernelRequest($event): void
    {
        $request = $event->getRequest();

        if (
            'security_register' !== $request->attributes->get('_route')
            && 'security_login' !== $request->attributes->get('_route')
            && 'app_logout' !== $request->attributes->get('_route')
            && 'security_forgot_password_request' !== $request->attributes->get('_route')
            && 'security_check_email' !== $request->attributes->get('_route')
            && 'security_reset_password' !== $request->attributes->get('_route')
            && 'account_index' !== $request->attributes->get('_route')
        ) {
            return;
        }

        $active = $this->config->get($this->extension_account_active) ? true : false;

        if(!$active) {
            throw new NotFoundHttpException('Extension Account is not active !');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 31]
        ];
    }
}
