<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class UserMailer
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    public function sendConfirmationEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject('Please confirm your email address')
            ->htmlTemplate('emails/email_confirmation.html.twig')
            ->context([
                'user' => $user,
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendPasswordResetEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject('Please confirm your email address')
            ->htmlTemplate('emails/password_reset.html.twig')
            ->context([
                'user' => $user,
            ])
        ;

        $this->mailer->send($email);
    }
}
