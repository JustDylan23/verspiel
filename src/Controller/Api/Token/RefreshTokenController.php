<?php

declare(strict_types=1);

namespace App\Controller\Api\Token;

use App\Manager\RefreshTokenManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RefreshTokenController extends AbstractController
{
    #[Route('/refresh', methods: ['POST'])]
    public function refresh(
        Request $request,
        JWTTokenManagerInterface $jwtManager,
        RefreshTokenManager $refreshTokenManager
    ): array {
        $refreshToken = $refreshTokenManager->retrieveRefreshToken($request);
        $refreshTokenManager->assertRefreshTokenValid($refreshToken);

        return [
            'token' => $jwtManager->create($refreshToken->getUser()),
        ];
    }

    #[Route('/fix', methods: ['GET'])]
    public function fix(): Response
    {
        $response = new Response(null, Response::HTTP_NO_CONTENT);
        $response->headers->clearCookie('REFRESH_TOKEN', '/api/token/refresh');
        return $response;
    }

    #[Route('/revoke', methods: ['POST'])]
    public function revoke(Request $request, RefreshTokenManager $refreshTokenManager): Response
    {
        $refreshToken = $refreshTokenManager->retrieveRefreshToken($request);

        $refreshTokenManager->assertRefreshTokenValid($refreshToken);
        $refreshTokenManager->revokeRefreshToken($refreshToken);

        $response = new Response(null, Response::HTTP_NO_CONTENT);
        $response->headers->clearCookie('REFRESH_TOKEN', '/api/token');
        return $response;
    }
}
