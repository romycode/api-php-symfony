<?php

declare(strict_types=1);

namespace App\Shared\Domain\Messaging\Query;

interface Query
{
    public function name(): string;
}
