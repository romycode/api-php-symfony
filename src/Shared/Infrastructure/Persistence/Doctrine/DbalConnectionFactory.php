<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

final class DbalConnectionFactory
{
    public static function create(string $databaseDsn): Connection
    {
        $parameters = self::parseDsn($databaseDsn);

        return DriverManager::getConnection($parameters);
    }

    private static function parseDsn(string $dsn): array
    {
        \preg_match(
            '/mysql:\/\/(?<user>.*):(?<password>.*)@(?<host>.*?)(?::(?<port>\d+))?(?:\/(?<dbname>.*))?$/i',
            $dsn,
            $parameters
        );

        $parameters['driver'] = 'pdo_mysql';

        return \array_filter($parameters, 'is_string', ARRAY_FILTER_USE_KEY);
    }
}
