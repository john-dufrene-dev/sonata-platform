<?php

declare(strict_types=1);

namespace App\Controller\Admin\Security\Action;

use DateTime;
use Twig\Environment;
use Psr\Log\NullLogger;
use Psr\Log\LoggerAwareTrait;
use Sonata\AdminBundle\Admin\Pool;
use Nucleos\UserBundle\Form\Model\Resetting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Nucleos\UserBundle\Form\Type\ResettingFormType;
use Symfony\Component\HttpFoundation\Session\Session;
use Nucleos\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sonata\AdminBundle\Templating\TemplateRegistryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class ResetAction
{
    use LoggerAwareTrait;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var Pool
     */
    private $adminPool;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var LoginManagerInterface
     */
    private $loginManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var int
     */
    private $passwordEncoder;

    /**
     * @var int
     */
    private $resetTtl;

    /**
     * @var string
     */
    private $firewallName;

    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $urlGenerator,
        AuthorizationCheckerInterface $authorizationChecker,
        Pool $adminPool,
        FormFactoryInterface $formFactory,
        UserManagerInterface $userManager,
        LoginManagerInterface $loginManager,
        TranslatorInterface $translator,
        SessionInterface $session,
        UserPasswordEncoderInterface $passwordEncoder,
        int $resetTtl = 7200,
        string $firewallName = 'admin'
    ) {
        $this->twig                 = $twig;
        $this->urlGenerator         = $urlGenerator;
        $this->authorizationChecker = $authorizationChecker;
        $this->adminPool            = $adminPool;
        $this->formFactory          = $formFactory;
        $this->userManager          = $userManager;
        $this->loginManager         = $loginManager;
        $this->translator           = $translator;
        $this->session              = $session;
        $this->passwordEncoder      = $passwordEncoder;
        $this->resetTtl             = $resetTtl;
        $this->firewallName         = $firewallName;
        $this->logger               = new NullLogger();
    }

    public function __invoke(Request $request, string $token): Response
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->urlGenerator->generate('sonata_admin_dashboard'));
        }

        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }

        if (!$user->isPasswordRequestNonExpired($this->resetTtl)) {
            return new RedirectResponse($this->urlGenerator->generate('nucleos_user_admin_resetting_request'));
        }

        $form = $this->formFactory->create(ResettingFormType::class, new Resetting($user), [
            'validation_groups' => ['ResetPassword', 'Default'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $user->setEnabled(true);

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $form->getData()->getPlainPassword(),
            ));

            $message = $this->translator->trans('resetting.flash.success', [], 'NucleosUserBundle');
            $this->session->getFlashBag()->add('success', $message);

            $response = new RedirectResponse($this->urlGenerator->generate('sonata_admin_dashboard'));

            try {
                $this->loginManager->logInUser($this->firewallName, $user, $response);
                $user->setLastLogin(new DateTime());
            } catch (AccountStatusException $ex) {
                // We simply do not authenticate users which do not pass the user
                // checker (not enabled, expired, etc.).
                $this->logger->warning(
                    sprintf(
                        'Unable to login user %d after password reset',
                        $user->getId()
                    ),
                    ['exception' => $ex]
                );
            }

            $this->userManager->updateUser($user);

            return $response;
        }

        return new Response(
            $this->twig->render(
                '@NucleosUserAdmin/Admin/Security/Resetting/reset.html.twig',
                [
                    'token'         => $token,
                    'form'          => $form->createView(),
                    'base_template' => '@SonataAdmin/standard_layout.html.twig',
                    'admin_pool'    => $this->adminPool,
                ]
            )
        );
    }
}
