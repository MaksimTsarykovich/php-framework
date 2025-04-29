<?php

namespace Tmi\Framework\Http\Middleware;

use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler):Response;
}