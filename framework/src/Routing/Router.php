<?php

namespace Tmi\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use League\Container\Container;
use Tmi\Framework\Controller\AbstractController;
use Tmi\Framework\Http\Exceptions\MethodNotAllowedException;
use Tmi\Framework\Http\Exceptions\RouteNotFoundException;
use Tmi\Framework\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouteInterface
{
    private array $routes;

    public function dispatch(Request $request, Container $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);

            if (is_subclass_of($controller, AbstractController::class)){
                $controller->setRequest($request);
            }

            $handler = [$controller, $method];
        }

        return [$handler, $vars];
    }

}
