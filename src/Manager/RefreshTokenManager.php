<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\RefreshToken;
use App\Repository\RefreshTokenRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;

class RefreshTokenManager
{
    public function __construct(
        private readonly RefreshTokenRepository $refreshTokenRepository,
        private readonly Security $security,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function generateRefreshToken(): RefreshToken
    {
        $refreshToken = RefreshToken::createForUserWithTtl($this->security->getUser(),
            $this->parameterBag->get('app.refresh_token_lifetime'));

        $this->refreshTokenRepository->add($refreshToken, true);

        return $refreshToken;
    }

    public function retrieveRefreshToken(string $refreshToken): ?RefreshToken
    {
        return $this->refreshTokenRepository->findOneBy(['refreshToken' => $refreshToken]);
    }

    public function assertRefreshTokenValid(?RefreshToken $refreshToken): void
    {
        if (null === $refreshToken || $refreshToken->isExpired()) {
            if (null !== $refreshToken) {
                $this->revokeRefreshToken($refreshToken);
            }
            throw new UnauthorizedHttpException('', 'Invalid refresh token');
        }
    }

    public function revokeRefreshToken(RefreshToken $refreshToken): void
    {
        $this->refreshTokenRepository->remove($refreshToken, true);
    }
}
