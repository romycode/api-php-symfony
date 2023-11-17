<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Symfony;

use App\Shared\Domain\Messaging\Event\Event;
use App\Shared\Domain\Messaging\Event\EventBus;
use App\Shared\Infrastructure\Messaging\Symfony\Stamps\EventNameStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function publish(Event ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch(new Envelope($event, [new EventNameStamp($event->name())]));
        }
    }
}
