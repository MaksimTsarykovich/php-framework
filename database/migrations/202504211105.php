<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable("posts");
        $table->addColumn("id", Types::INTEGER, [
            "autoincrement" => true,
            "unsigned" => true
        ]);
        $table->addColumn("title", Types::STRING, ['length' => 255]);
        $table->addColumn("content", Types::TEXT);
        $table->addColumn("created_at", Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        //
    }
};
