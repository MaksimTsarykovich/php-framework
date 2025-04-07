<?php

namespace Tmi\Framework\Routing;

use League\Container\Container;
use Tmi\Framework\Http\Request;

interface RouteInterface
{
    public function dispatch(Request $request, Container $container);

    public function registerRoutes(array $routes);
}
