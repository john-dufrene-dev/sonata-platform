<?php

namespace App\Service\Email;

use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Notifier
 */
class Notifier
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
     * __construct
     *
     * @param  mixed $mailer
     * @param  mixed $params
     * @param  mixed $em
     * @return void
     */
    public function __construct(MailerInterface $mailer, ParameterBagInterface $params, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->params = $params;
        $this->em = $em;
    }

    /**
     * EmailRegistration
     *
     * @return void
     */
    public function EmailRegistration(
        $user,
        $subject = 'Email Registration',
        $text = 'Email registration text',
        $template = 'front/emails/users/registration.html.twig'
    ) {
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get('mailer_user'), $subject))
            ->to($user->getEmail())
            ->subject($subject)
            ->text($text)
            ->htmlTemplate($template)
            ->context(['user' => $user]);

        $this->mailer->send($email);
    }


    /**
     * EmailResetPassword
     *
     * @return void
     */
    public function EmailResetPassword(
        $user,
        $token,
        $lifetime,
        $subject = 'Your password reset request',
        $template = 'front/emails/users/reset.password.html.twig'
    ) {
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get('mailer_user'), $subject))
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'resetToken' => $token,
                'tokenLifetime' => $lifetime,
            ]);

        $this->mailer->send($email);
    }
}
