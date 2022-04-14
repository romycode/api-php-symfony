<?php

declare(strict_types=1);

namespace App\Shared\Domain\Messaging\Event;

interface EventSubscriber
{
    public static function subscribedTo(): array;
}
