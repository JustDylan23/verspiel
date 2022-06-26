<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Manager\RefreshTokenManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly RefreshTokenManager $refreshTokenManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $refreshToken = $this->refreshTokenManager->generateRefreshToken();
        $data['refresh_token'] = $refreshToken->getRefreshToken();
        $data['refresh_token_expiration'] = $refreshToken->getExpiresAt()->getTimestamp();

        $event->setData($data);

        $event->getResponse()->headers->setCookie(Cookie::create('REFRESH_TOKEN', $refreshToken->getRefreshToken(), $refreshToken->getExpiresAt(), '/api/token'));
    }
}
