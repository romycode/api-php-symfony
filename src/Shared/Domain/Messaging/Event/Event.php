<?php

declare(strict_types=1);

namespace App\Shared\Domain\Messaging\Event;

interface Event
{
    public function name(): string;
}
