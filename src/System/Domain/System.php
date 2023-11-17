<?php

declare(strict_types=1);

namespace App\System\Domain;

final class System
{
    public function __construct(private bool $databaseStatus)
    {
    }

    public function getDatabaseStatus(): bool
    {
        return $this->databaseStatus;
    }
}
