<?php

namespace App\EventSubscriber\User;

use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IsAuthenticatedUserSubscriber implements EventSubscriberInterface
{    
    /**
     * tokenStorage
     *
     * @var mixed
     */
    protected $tokenStorage;
    
    /**
     * jwt
     *
     * @var mixed
     */
    protected $jwt;
    
    /**
     * __construct
     *
     * @param  mixed $tokenStorage
     * @param  mixed $jwt
     * @return void
     */
    public function __construct(TokenStorageInterface $tokenStorage, JWTTokenManagerInterface $jwt) {
        $this->tokenStorage = $tokenStorage;
        $this->jwt = $jwt;
    }
    
    /**
     * beforeKernelResponse
     *
     * @param  mixed $event
     * @return void
     */
    public function beforeKernelResponse(ResponseEvent  $event): void
    {
        if($event->getRequest()->cookies->has('X-AUTH-TOKEN')) {
            return;
        }
    
        if (!$token = $this->tokenStorage->getToken()) {
            return ;
        }

        if (!$token->getUser() instanceof User) {
            return;
        }

        $response = $event->getResponse();

        $apiToken = $this->jwt->create($token->getUser());
        $cookie = new Cookie('X-AUTH-TOKEN', $apiToken, time() + 3600);
        $response->headers->setCookie($cookie);
    }
    
    /**
     * getSubscribedEvents
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['beforeKernelResponse']
        ];
    }	
}