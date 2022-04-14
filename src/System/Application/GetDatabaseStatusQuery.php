<?php

declare(strict_types=1);

namespace App\System\Application;

use App\Shared\Domain\Messaging\Query\Query;

final class GetDatabaseStatusQuery implements Query
{
    public function name(): string
    {
        return 'system.get.database_status';
    }
}
