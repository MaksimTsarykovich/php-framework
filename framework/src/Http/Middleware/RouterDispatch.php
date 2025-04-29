<?php

namespace Tmi\Framework\Http\Middleware;

use Psr\Container\ContainerInterface;
use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;
use Tmi\Framework\Routing\RouteInterface;

class RouterDispatch implements MiddlewareInterface
{

    public function __construct(
        private RouteInterface $router,
        private ContainerInterface $container,
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

        $response = call_user_func_array($routeHandler, $vars);

        return $response;
    }
}