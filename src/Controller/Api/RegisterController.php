<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\DTO\RegistrationDTO;
use App\Entity\User;
use App\Manager\UserMailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractRestController
{
    #[Route('/users/register', methods: ['POST'])]
    public function register(UserPasswordHasherInterface $passwordHasher, UserMailer $userMailer, Request $request, RateLimiterFactory $registerLimiter): void
    {
        $limiter = $registerLimiter->create($request->getClientIp());
        $limiter->consume()->ensureAccepted();

        $registrationDTO = new RegistrationDTO();
        $this->deserializeRequestContent($registrationDTO);

        $this->assertValid($registrationDTO);

        $user = new User();
        $user->setEmail($registrationDTO->email);
        $user->setUsername($registrationDTO->username);
        $user->setPassword($registrationDTO->password);

        $this->assertValid($user);

        $user->setPassword($passwordHasher->hashPassword($user, $registrationDTO->password));
        $user->generateEmailVerificationToken();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userMailer->sendConfirmationEmail($user);
    }
}
