<?php

namespace App\Controller\Front\Security\User;

use App\Entity\User\User;
use App\Entity\User\UserInfo;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use App\Security\User\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Front\Security\RegistrationFormType;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    protected $seo;

    public function __construct(SeoPageInterface $seo)
    {
        $this->seo = $seo;
    }
    
    /**
     * @Route("/register", name="front_pages_security_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('front_pages_account_index');
        }

        $this->seo
            ->addTitle('Page d\'inscription') // you can use setTitle($title)
            ->addMeta('name', 'robots', 'index, follow')
            ->addMeta('name', 'description', 'Description page d\'inscription');

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

            if($guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main_user')) {
                return $this->redirectToRoute('front_pages_account_index');
            }

        }

        return $this->render('front/pages/security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
