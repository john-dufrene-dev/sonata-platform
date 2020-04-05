<?php

namespace App\Controller\Front\Security\User;

use App\Entity\User\User;
use App\Entity\User\UserInfo;
use App\Service\Email\Notifier;
use App\Service\ConfigurationBuilder;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use App\Security\User\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Front\Security\RegistrationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RegistrationController extends AbstractController
{
    /**
     * config
     *
     * @var mixed
     */
    protected $config;

    /**
     * seo
     *
     * @var mixed
     */
    protected $seo;

    /**
     * params
     *
     * @var mixed
     */
    protected $params;

    /**
     * __construct
     *
     * @param  mixed $seo
     * @param  mixed $params
     * @return void
     */
    public function __construct(
        ConfigurationBuilder $config,
        SeoPageInterface $seo,
        ParameterBagInterface $params
    ) {
        $this->config = $config;
        $this->seo = $seo;
        $this->params = $params;
    }

    /**
     *
     * @param  mixed $request
     * @param  mixed $passwordEncoder
     * @param  mixed $guardHandler
     * @param  mixed $authenticator
     * @param  mixed $notifier
     * @return Response
     * @Route("/account/register", name="security_register")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        Notifier $notifier
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('account_index');
        }

        $valueSeo = $this->config->getSeoValue('REGISTER');

        $this->seo
            ->addTitle($valueSeo['title'] ?? '') // you can use setTitle($title)
            ->addMeta('name', 'robots', $valueSeo['index'] ?? '')
            ->addMeta('name', 'description', $valueSeo['description'] ?? '');

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $infos = new UserInfo();
            $user->setInfos($infos);

            $user->setRoles(['ROLE__USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if (
                true == $this->params->has('mailer_user')
                && $this->params->get('mailer_user') != 'contact@domain.com'
            ) {
                // Send email registration to user
                $notifier->emailRegistration($user);
            }

            if ($guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main_user')) {
                return $this->redirectToRoute('account_index');
            }
        }

        return $this->render('front/pages/security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
