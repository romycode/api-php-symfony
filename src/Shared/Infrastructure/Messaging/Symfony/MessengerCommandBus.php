<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Symfony;

use App\Shared\Domain\Messaging\Command\Command;
use App\Shared\Domain\Messaging\Command\CommandBus;
use App\Shared\Infrastructure\Messaging\Symfony\Stamps\CommandNameStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    final public function dispatch(Command $command): void
    {
        try {
            $this->commandBus->dispatch(new Envelope($command), [new CommandNameStamp($command->name())]);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious() ?? $exception;
        }
    }
}
