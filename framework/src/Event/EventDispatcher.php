<?php

namespace Tmi\Framework\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{

    private array $listeners = [];

    public function dispatch(object $event): object
    {
        foreach ($this->getListenersForEvent($event) as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }

            $listener($event);
        }

        return $event;
    }

    public function addListener(string $event, callable $listener): static
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function getListenersForEvent(object $event): iterable
    {
        $eventClass = get_class($event);

        if (array_key_exists($eventClass, $this->listeners)) {
            return $this->listeners[$eventClass];
        }

        return [];
    }
}