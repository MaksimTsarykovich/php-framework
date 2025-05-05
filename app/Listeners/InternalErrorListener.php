<?php

namespace App\Listeners;

use Tmi\Framework\Http\Events\ResponseEvent;

class InternalErrorListener
{
    public function __invoke(ResponseEvent $event)
    {
        if ($event->getResponse()->getStatusCode() >= 500){
            $event->stopPropagation();
        }
    }

}