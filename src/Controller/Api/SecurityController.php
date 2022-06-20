<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SecurityController extends AbstractRestController
{
    protected const ITEM_ATTRIBUTES = [
        'id',
        'username',
        'email',
        'badges',
    ];

    protected const WRITE_ATTRIBUTES = [
        'username',
    ];

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): array|Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->viewItem($user);
    }

    #[Route('/users/@me', name: 'api_user_get_item', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function user(#[CurrentUser] ?User $user): array|Response
    {
        return $this->viewItem($user);
    }

    #[Route('/users/@me', name: 'api_user_patch', methods: ['PATCH'])]
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
