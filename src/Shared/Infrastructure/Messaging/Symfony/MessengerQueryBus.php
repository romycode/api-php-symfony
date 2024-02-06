<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Symfony;

use App\Shared\Domain\Messaging\Query\Query;
use App\Shared\Domain\Messaging\Query\QueryBus;
use App\Shared\Domain\Messaging\Response;
use App\Shared\Infrastructure\Messaging\Symfony\Stamps\QueryNameStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    public function ask(Query $query): Response
    {
        try {
            $envelope = $this->queryBus->dispatch(new Envelope($query, [new QueryNameStamp($query->name())]));

            /** @var HandledStamp $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);

            return $handledStamp->getResult();
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious() ?? $exception;
        }
    }
}
