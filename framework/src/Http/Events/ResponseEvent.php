<?php

namespace Tmi\Framework\Http\Events;

use Tmi\Framework\Event\Event;
use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private readonly Request  $request,
        private readonly Response $response
    )
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}