<?php

namespace Tmi\Framework\Console\Commands;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Tmi\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';
    private const MIGRATION_TABLE_NAME = 'migrations';

    public function __construct(
        private Connection $connection,
        private string     $migrationPath,
    )
    {
    }

    public function execute(array $parameters = []): int
    {
        try {
            $this->connection->setAutoCommit(false);

            $this->createMigrationsTable();
            $this->connection->beginTransaction();

            $appliedMigrations = $this->getAppliedMigrations();

            $migrationFiles = $this->getMigrationsFiles();

            $migrationToApply = array_diff($migrationFiles, $appliedMigrations);

            $schema = new Schema();

            foreach ($migrationToApply as $migration) {
                $migrationInstance = require $this->migrationPath . "/$migration";

                $migrationInstance->up($schema);

                $this->addMigration($migration);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql){
                $this->connection->executeQuery($sql);
            }

            $this->connection->commit();

        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }

        $this->connection->setAutoCommit(true);

        return 0;
    }

    public function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist([self::MIGRATION_TABLE_NAME])) {
            return;
        }

        $schema = new Schema();
        $table = $schema->createTable(self::MIGRATION_TABLE_NAME);
        $table->addColumn(
            'id',
            Types::INTEGER,
            [
                'unsigned' => true,
                'autoincrement' => true
            ]);
        $table->addColumn('migration', Types::STRING, ['length' => 255]);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE,
            [
                'default' => 'CURRENT_TIMESTAMP',
            ]
        );
        $table->setPrimaryKey(['id']);

        $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

        $this->connection->executeQuery($sqlArray[0]);

        echo 'Migrations table created' . PHP_EOL;
    }

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder->select('migration')
            ->from(self::MIGRATION_TABLE_NAME)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationsFiles(): array
    {
        $migrationsFiles = scandir($this->migrationPath);

        $filteredFiles = array_filter($migrationsFiles, function ($fileName) {
            return !in_array($fileName, ['.', '..']);
        });

        return array_values($filteredFiles);
    }

    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert(self::MIGRATION_TABLE_NAME)
            ->values(['migration' => ':migration'])
            ->setParameter('migration', $migration)
            ->executeQuery();

    }

}