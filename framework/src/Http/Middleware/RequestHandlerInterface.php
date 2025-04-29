<?php

namespace Tmi\Framework\Http\Middleware;

use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;

}