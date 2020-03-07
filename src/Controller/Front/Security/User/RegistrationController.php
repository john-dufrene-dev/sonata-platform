<?php

namespace App\Controller\Front\Security\User;

use App\Entity\User\User;
use App\Entity\User\UserInfo;
use Symfony\Component\Mime\Address;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Security\User\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Front\Security\RegistrationFormType;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RegistrationController extends AbstractController
{
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

    public function __construct(SeoPageInterface $seo, ParameterBagInterface $params)
    {
        $this->seo = $seo;
        $this->params = $params;
    }

    /**
     * @Route("/account/register", name="security_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        MailerInterface $mailer
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('account_index');
        }

        $this->seo
            ->addTitle($title ?? $this->params->get('seo.pages.security_register.title')) // you can use setTitle($title)
            ->addMeta('name', 'robots', $robots ?? $this->params->get('seo.pages.security_register.robots'))
            ->addMeta('name', 'description', $description ?? $this->params->get('seo.pages.security_register.description'));

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

            // do anything else you need here, like send an email

            if ($guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main_user')) {

                if (
                    true == $this->params->has('mailer_user')
                    && $this->params->get('mailer_user') != 'default@default.fr'
                ) {
                    $this->emailRegistration($mailer, $user);
                }

                return $this->redirectToRoute('account_index');
            }
        }

        return $this->render('front/pages/security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function EmailRegistration(MailerInterface $mailer, $user)
    {
        $email = (new TemplatedEmail())
            ->from($this->params->get('mailer_user'))
            ->to(new Address($user->getEmail()))
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('front/emails/users/registration.html.twig')
            ->context(['user' => $user]);

        $mailer->send($email);
    }
}
