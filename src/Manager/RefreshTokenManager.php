<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\RefreshToken;
use App\Repository\RefreshTokenRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;

class RefreshTokenManager
{
    public function __construct(
        private readonly RefreshTokenRepository $refreshTokenRepository,
        private readonly Security $security,
        private readonly ParameterBagInterface $parameterBag,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function generateRefreshToken(): RefreshToken
    {
        $refreshToken = RefreshToken::createForUserWithTtl(
            $this->security->getUser(),
            $this->parameterBag->get('app.refresh_token_lifetime'),
            $this->requestStack->getCurrentRequest()->getClientIp(),
        );

        $this->refreshTokenRepository->add($refreshToken, true);

        return $refreshToken;
    }

    public function retrieveRefreshToken(Request $request): ?RefreshToken
    {
        $refreshToken = $request->cookies->get('REFRESH_TOKEN', $request->getContent());

        return $this->refreshTokenRepository->findOneBy(['refreshToken' => $refreshToken]);
    }

    public function assertRefreshTokenValid(?RefreshToken $refreshToken): void
    {
        if (null === $refreshToken) {
            throw new UnauthorizedHttpException('', 'Invalid refresh token');
        }
        if ($refreshToken->isExpired()) {
            $this->revokeRefreshToken($refreshToken);
            throw new UnauthorizedHttpException('', 'Expired refresh token');
        }
    }

    public function revokeRefreshToken(RefreshToken $refreshToken): void
    {
        $this->refreshTokenRepository->remove($refreshToken, true);
    }
}
