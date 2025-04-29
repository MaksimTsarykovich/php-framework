<?php

namespace Tmi\Framework\Http\Middleware;

use Tmi\Framework\Http\Middleware\MiddlewareInterface;
use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;
use Tmi\Framework\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }
}