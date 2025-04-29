<?php

namespace Tmi\Framework\Http\Middleware;

use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;

class Authenticate implements MiddlewareInterface
{

    private bool $auth = true;

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->auth) {
            return new Response('Authentication failed', 401);
        }

        return $handler->handle($request);
    }
}