<?php

namespace App\EventSubscriber\User\Api;

use App\Entity\User\User;
use App\Service\Email\Notifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class SendEmailUserApiSubscriber implements EventSubscriberInterface
{
    /**
     * params
     *
     * @var mixed
     */
    protected $params;

    /**
     * notifier
     *
     * @var mixed
     */
    protected $notifier;

    /**
     * __construct
     *
     * @param  mixed $params
     * @param  mixed $notifier
     * @return void
     */
    public function __construct(ParameterBagInterface $params, Notifier $notifier)
    {
        $this->params = $params;
        $this->notifier = $notifier;
    }

    /**
     * getSubscribedEvents
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    /**
     * sendMail
     *
     * @param  mixed $event
     * @return void
     */
    public function sendMail($event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        if (
            true == $this->params->has('mailer_user')
            && $this->params->get('mailer_user') != 'contact@domain.com'
        ) {
            // Send email registration to user
            $this->notifier->emailRegistration($user);
        }
    }
}
