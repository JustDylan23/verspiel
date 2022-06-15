<?php

declare(strict_types=1);

namespace App\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Notifier\Bridge\Discord\DiscordOptions;
use Symfony\Component\Notifier\Bridge\Discord\Embeds\DiscordEmbed;
use Symfony\Component\Notifier\Bridge\Discord\Embeds\DiscordFieldEmbedObject;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

class DiscordNotifier extends AbstractProcessingHandler
{
    public function __construct(
        private readonly ChatterInterface $chatter,
        private readonly UrlHelper $urlHelper,
        private readonly RequestStack $requestStack,
    ) {
        parent::__construct(Level::Critical, false);
    }


    public function write(LogRecord $record): void
    {
        $message = new ChatMessage('');
        $message->transport('discord');

        $discordOptions = (new DiscordOptions())
            ->username('Verspiel logger')
            ->avatarUrl($this->urlHelper->getAbsoluteUrl('/android-chrome-192x192.png'))
            ->addEmbed((new DiscordEmbed())
                ->timestamp((new \DateTime())->setTimestamp($record->datetime->getTimestamp()))
                ->title('Internal server error')
                ->description($record->message)
                ->color(12289788)
                ->addField((new DiscordFieldEmbedObject())
                    ->name('Request URI')
                    ->value($this->requestStack->getCurrentRequest()->getRequestUri())
                )
                ->addField((new DiscordFieldEmbedObject())
                    ->name('Channel')
                    ->value($record->channel)
                )
                ->addField((new DiscordFieldEmbedObject())
                    ->name('Stacktrace')
                    ->value("```m\n".$record->context['exception']->getTraceAsString().'```')
                )
            )
        ;
        $message->options($discordOptions);

        $this->chatter->send($message);
    }
}
