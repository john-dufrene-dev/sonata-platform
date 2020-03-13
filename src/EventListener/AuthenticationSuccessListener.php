<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

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
