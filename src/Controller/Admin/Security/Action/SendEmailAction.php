<?php

declare(strict_types=1);

namespace App\Controller\Admin\Security\Action;

use DateTime;
use Twig\Environment;
use Sonata\AdminBundle\Admin\Pool;
use App\Service\Email\AdminNotifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Nucleos\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SendEmailAction
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var Pool
     */
    private $adminPool;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var Notifier
     */
    private $notifier;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var int
     */
    private $resetTtl;

    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $urlGenerator,
        Pool $adminPool,
        UserManagerInterface $userManager,
        AdminNotifier $notifier,
        TokenGeneratorInterface $tokenGenerator,
        int $resetTtl = 7200
    ) {
        $this->twig             = $twig;
        $this->urlGenerator     = $urlGenerator;
        $this->adminPool        = $adminPool;
        $this->userManager      = $userManager;
        $this->notifier           = $notifier;
        $this->tokenGenerator   = $tokenGenerator;
        $this->resetTtl         = $resetTtl;
    }

    public function __invoke(Request $request): Response
    {
        $username = $request->request->get('username');

        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return new Response($this->twig->render('@NucleosUserAdmin/Admin/Security/Resetting/request.html.twig', [
                'base_template'    => '@SonataAdmin/standard_layout.html.twig',
                'admin_pool'       => $this->adminPool,
                'invalid_username' => $username,
            ]));
        }

        if (!$user->isPasswordRequestNonExpired($this->resetTtl)) {
            if (!$user->isAccountNonLocked()) {
                return new RedirectResponse(
                    $this->urlGenerator->generate('nucleos_user_admin_resetting_request')
                );
            }

            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->notifier->sendResettingEmailMessage($user);
            $user->setPasswordRequestedAt(new DateTime());
            $this->userManager->updateUser($user);
        }

        return new RedirectResponse($this->urlGenerator->generate('nucleos_user_admin_resetting_check_email', [
            'username' => $username,
        ]));
    }
}
