<?php

namespace App\EventSubscriber\Redirect;

use Symfony\Component\HttpKernel\KernelEvents;
use App\Repository\Redirect\RedirectRepository;
use App\Service\Cache\RedirectCacheBuilder;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RedirectSubscriber implements EventSubscriberInterface
{
    protected $repository;

    protected $cacheredirect;

    public function __construct(RedirectRepository $repository, RedirectCacheBuilder $cacheredirect)
    {
        $this->repository = $repository;
        $this->cacheredirect = $cacheredirect;
    }

    public function beforeKernelResponse($event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($redirect = $this->cacheredirect->get($event->getRequest()->getPathInfo())) {
            $event->setResponse(
                new RedirectResponse($redirect['destination'], (int) $redirect['code'])
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['beforeKernelResponse', 256]
        ];
    }
}
