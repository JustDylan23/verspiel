<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    public function onLoginSuccessEvent(LoginSuccessEvent $event)
    {
        $event->getPassport()->addBadge(new RememberMeBadge());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccessEvent',
        ];
    }
}
