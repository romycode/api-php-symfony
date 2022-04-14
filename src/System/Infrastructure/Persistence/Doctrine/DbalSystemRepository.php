<?php

declare(strict_types=1);

namespace App\System\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Logger;
use App\System\Domain\System;
use App\System\Domain\SystemRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DbalException;

final class DbalSystemRepository implements SystemRepository
{
    public function __construct(private Connection $connection, private Logger $logger)
    {
    }

    public function getDatabaseStatus(): System
    {
        try {
            $result = $this->connection->prepare('SELECT 1 + 1;')->executeQuery()->fetchAllAssociative();
        } catch (DbalException $e) {
            dd($e);
            $this->logger->error($e->getMessage(), $e->getTrace());
            return new System(false);
        }

        return new System(true);
    }
}
