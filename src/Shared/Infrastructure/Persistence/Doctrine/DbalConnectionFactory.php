<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Tools\DsnParser;

final class DbalConnectionFactory
{
    /** @throws Exception */
    public static function create(array $configuration): Connection
    {
        return DriverManager::getConnection((new DsnParser($configuration['available_drivers']))->parse($configuration['dsn']));
    }
}
