<?php

namespace App\EventListener;

use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        // you can inject user infos in jwt token
        // $user = $event->getUser();

        // if (!$user instanceof UserInterface) {
        //     return;
        // }

        $event->setData([
            'token'         => $event->getData()['token'],
            'status'        => $event->getResponse()->getStatusCode(),
            'expire_in'     => 3600,
        ]);
    }
}
