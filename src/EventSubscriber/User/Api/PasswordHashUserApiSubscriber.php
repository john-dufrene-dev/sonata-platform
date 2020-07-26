<?php

namespace App\EventSubscriber\User\Api;

use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordHashUserApiSubscriber implements EventSubscriberInterface
{
    /**
     * passwordEncoder
     *
     * @var mixed
     */
    protected $passwordEncoder;

    /**
     * __construct
     *
     * @param  mixed $passwordEncoder
     * @return void
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * getSubscribedEvents
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * hashPassword
     *
     * @param  mixed $event
     * @return void
     */
    public function hashPassword($event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        // Verify if it's an instance of the User and METHOD == PUT/PATCH/POST
        if (!$user instanceof User || (Request::METHOD_POST !== $method
            && Request::METHOD_PUT !== $method && Request::METHOD_PATCH !== $method)) {
            return;
        }

        // It is an User instance, we need to hash the password in Api
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
    }
}
