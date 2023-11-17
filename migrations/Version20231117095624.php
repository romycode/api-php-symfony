<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20231117095624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add test table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('test');

        $table
            ->addColumn('id', Types::INTEGER)
            ->setAutoincrement(true);

        $table->setPrimaryKey(['id'], 'idx_test_id');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('test');
    }
}
