<?php

namespace App\Listeners;

use Tmi\Framework\Dbal\Event\EntityPersist;

class HandleEntityListener
{
    public function __invoke(EntityPersist $event)
    {
        dd($event->getEntity());
    }
}