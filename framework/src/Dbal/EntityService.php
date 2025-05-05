<?php

namespace Tmi\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Tmi\Framework\Dbal\Event\EntityPersist;
use Tmi\Framework\Event\EventDispatcher;

class EntityService
{
    public function __construct(
        private Connection $connection,
        private EventDispatcher $eventDispatcher
    )
    {
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function save(Entity $entity): int
    {
        $entityId = $this->connection->lastInsertId();

        $entity->setId($entityId);

        $this->eventDispatcher->dispatch(new EntityPersist($entity));

        return $entityId;
    }
}