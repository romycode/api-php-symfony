<?php

declare(strict_types=1);

namespace App\Shared\Domain\Messaging\Command;

interface Command
{
    public function name(): string;
}
