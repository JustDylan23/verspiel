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
        if (null === $this->requestStack->getCurrentRequest()) {
            return;
        }
        $message = new ChatMessage('');
        $message->transport('discord');

        $discordOptions = (new DiscordOptions())
            ->username('Verspiel logger')
            ->avatarUrl($this->urlHelper->getAbsoluteUrl('/android-chrome-192x192.png'))
            ->addEmbed(
                (new DiscordEmbed())
                ->color(12289788)
                ->timestamp((new \DateTime())->setTimestamp($record->datetime->getTimestamp()))
                ->title('Internal server error')
                ->description(empty($record->message) ? 'No message' : $record->message)
                ->addField(
                    (new DiscordFieldEmbedObject())
                    ->name('Request URI')
                    ->value($this->requestStack?->getCurrentRequest()?->getRequestUri() ?? 'N/A')
                )
                ->addField(
                    (new DiscordFieldEmbedObject())
                    ->name('Channel')
                    ->value(empty($record->channel) ? 'N/A' : $record->channel)
                )
            )
        ;
        $message->options($discordOptions);

        $this->chatter->send($message);
    }

    private function encode($value): string
    {
        if ($value instanceof \Exception) {
            return substr($value->getTraceAsString(), 0, 50);
        }
        if ($value instanceof \Error) {
            return substr($value->getTraceAsString(), 0, 50);
        }

        return json_encode($value);
    }
}
