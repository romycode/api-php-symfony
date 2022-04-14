<?php

declare(strict_types=1);

namespace App\Shared\Domain\Messaging\Event;

interface EventBus
{
    public function publish(Event ...$events): void;
}
