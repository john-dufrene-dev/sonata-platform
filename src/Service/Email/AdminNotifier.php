<?php

namespace App\Service\Email;

use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Nucleos\UserBundle\Model\UserInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Nucleos\UserBundle\Mailer\Mail\ResettingMail;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * AdminNotifier
 */
class AdminNotifier
{
    /**
     * mailer
     *
     * @var mixed
     */
    protected $mailer;

    /**
     * params
     *
     * @var mixed
     */
    protected $params;

    /**
     * em
     *
     * @var mixed
     */
    protected $em;

    /**
     * translator
     *
     * @var mixed
     */
    protected $translator;

    /**
     * router
     *
     * @var mixed
     */
    protected $router;

    /**
     * __construct
     *
     * @param  mixed $mailer
     * @param  mixed $params
     * @param  mixed $em
     * @param  mixed $translator
     * @param  mixed $router
     * @return void
     */
    public function __construct(MailerInterface $mailer, ParameterBagInterface $params, EntityManagerInterface $em, TranslatorInterface $translator, UrlGeneratorInterface $router)
    {
        $this->mailer       = $mailer;
        $this->params       = $params;
        $this->em           = $em;
        $this->translator   = $translator;
        $this->router       = $router;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendResettingEmailMessage(UserInterface $user): void
    {
        $url  = $this->router->generate('nucleos_user_admin_resetting_reset', [
            'token' => $user->getConfirmationToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $mail = (new ResettingMail())
            ->from(Address::fromString($this->params->get('mailer_user')))
            ->to(new Address($user->getEmail()))
            ->subject($this->translator->trans('resetting.email.subject', [
                '%username%' => $user->getUsername(),
            ], 'NucleosUserBundle'))
            ->setUser($user)
            ->setConfirmationUrl($url)
        ;

        $this->mailer->send($mail);
    }
}
