<?php

declare(strict_types=1);

namespace App\System\Application;

use App\Shared\Domain\Messaging\Response;
use App\System\Domain\System;

final class GetDatabaseStatusResponse implements Response
{
    public function __construct(private bool $success)
    {
    }

    public static function fromSystem(System $system): self
    {
        return new self(
            $system->getDatabaseStatus()
        );
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }
}
