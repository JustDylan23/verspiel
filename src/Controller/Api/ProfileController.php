<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProfileController extends AbstractRestController
{
    protected const WRITE_ATTRIBUTES = [
        'username',
    ];

    #[Route('/users/me', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateUser(#[CurrentUser] ?User $user, Request $request, RateLimiterFactory $usernameChangeLimiter): ConstraintViolationListInterface|array
    {
        $this->deserializeRequestContent($user);

        $violations = $this->getViolations($user);

        if ($violations->count() > 0) {
            $this->entityManager->refresh($user);
            return $violations;
        }

        $limiter = $usernameChangeLimiter->create($request->getClientIp());
        $limiter->consume()->ensureAccepted();


        return $this->viewPatch($user);
    }
}
