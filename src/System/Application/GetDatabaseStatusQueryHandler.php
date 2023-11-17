<?php

declare(strict_types=1);

namespace App\System\Application;

use App\Shared\Domain\Messaging\Query\QueryHandler;
use App\System\Domain\SystemRepository;

final class GetDatabaseStatusQueryHandler implements QueryHandler
{
    public function __construct(private SystemRepository $repository)
    {
    }

    public function __invoke(GetDatabaseStatusQuery $_): GetDatabaseStatusResponse
    {
        $databaseStatus = $this->repository->getDatabaseStatus();

        return GetDatabaseStatusResponse::fromSystem($databaseStatus);
    }
}
