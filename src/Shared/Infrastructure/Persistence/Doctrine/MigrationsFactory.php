<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;

final class MigrationsFactory
{
    public static function create(array $configuration, Connection $connection): DependencyFactory
    {
        $ignored_tables = $configuration['ignored_tables'] ?? [];
        unset($configuration['ignored_tables']);

        $configuration = new ConfigurationArray($configuration);
        $loader = new ExistingConnection($connection);

        $connection->getConfiguration()->setSchemaAssetsFilter(
            static function (string $table) use ($ignored_tables) {
                return !\in_array($table, $ignored_tables);
            }
        );

        return DependencyFactory::fromConnection($configuration, $loader);
    }
}
