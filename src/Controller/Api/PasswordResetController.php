<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\DTO\EmailDTO;
use App\Entity\DTO\PasswordResetDTO;
use App\Manager\UserMailer;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractRestController
{
    #[Route('/password-reset/request', name: 'api_password_reset_request', methods: ['POST'])]
    public function request(
        RateLimiterFactory $passwordResetLimiter,
        UserRepository $userRepository,
        UserMailer $userMailer
    ) {
        $emailDTO = new EmailDTO();
        $this->deserializeRequestContent($emailDTO);

        $this->assertValid($emailDTO);

        $limiter = $passwordResetLimiter->create($emailDTO->email);
        $limiter->consume()->ensureAccepted();

        $user = $userRepository->findOneBy(['email' => $emailDTO->email]);

        if (null === $user) {
            return; // return instead of throwing error because we don't want to leak information about whether a user exists or not
        }

        $user->generatePasswordResetToken();
        $this->entityManager->flush();

        $userMailer->sendPasswordResetEmail($user);
    }

    #[Route('/password-reset/complete', name: 'api_password_reset_complete', methods: ['POST'])]
    public function complete(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $passwordResetDTO = new PasswordResetDTO();
        $this->deserializeRequestContent($passwordResetDTO);

        $this->assertValid($passwordResetDTO);

        $user = $userRepository->findOneBy(['passwordResetToken' => $passwordResetDTO->token]);

        if (null === $user) {
            throw $this->createNotFoundException('Invalid token');
        }

        if ($user->isPasswordResetTokenExpired()) {
            throw $this->createNotFoundException('Expired token');
        }

        $user->setPassword($passwordHasher->hashPassword($user, $passwordResetDTO->password));

        $this->entityManager->flush();
    }
}
