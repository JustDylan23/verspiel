<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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

        $this->send($email);
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

        $this->send($email);
    }

    private function send($email): void
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
            throw new BadRequestHttpException('Failed to send email');
        }
    }
}
