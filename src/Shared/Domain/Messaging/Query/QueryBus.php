<?php

declare(strict_types=1);

namespace App\Shared\Domain\Messaging\Query;

use App\Shared\Domain\Messaging\Response;

interface QueryBus
{
    public function ask(Query $query): Response;
}
