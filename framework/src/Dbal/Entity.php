<?php

namespace Tmi\Framework\Dbal;

abstract class Entity
{
    abstract public function setId(?int $id);
}