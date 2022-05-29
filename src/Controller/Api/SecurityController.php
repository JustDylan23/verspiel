<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractRestController
{
    protected const ITEM_ATTRIBUTES = [
        'id',
        'username',
        'email',
        'isAdmin'
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

    #[Route('/users/me', methods: ['GET'])]
    public function user(#[CurrentUser] ?User $user): array|Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->viewItem($user);
    }
}
