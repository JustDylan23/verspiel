<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException('Your account is not verified. Please check your email.');
        }

        if ($user->isDisabled()) {
            throw new CustomUserMessageAccountStatusException('Your account has been disabled.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
