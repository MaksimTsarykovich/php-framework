<?php

namespace Tmi\Framework\Dbal\Event;

use Tmi\Framework\Dbal\Entity;
use Tmi\Framework\Event\Event;

class EntityPersist extends Event
{
    public function __construct(
        private Entity $entity,
    )
    {
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }
}