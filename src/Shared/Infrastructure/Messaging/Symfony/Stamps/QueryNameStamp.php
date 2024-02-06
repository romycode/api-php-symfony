<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Symfony\Stamps;

use Symfony\Component\Messenger\Stamp\StampInterface;

class QueryNameStamp implements StampInterface
{
    public function __construct(public readonly string $name)
    {
    }
}
