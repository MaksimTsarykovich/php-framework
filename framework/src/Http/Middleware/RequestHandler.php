<?php

namespace Tmi\Framework\Http\Middleware;

use Psr\Container\ContainerInterface;
use Tmi\Framework\Http\Middleware\RequestHandlerInterface;
use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;

class RequestHandler implements RequestHandlerInterface
{

    private array $middleware = [
        ExtractRouteInfo::class,
        StartSession::class,
        RouterDispatch::class,
    ];

    public function __construct(
        private ContainerInterface $container,
    )
    {
    }

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            return new Response('Server Error', 500);
        }

        $middlewareClass = array_shift($this->middleware);

        $middleware = $this->container->get($middlewareClass);

        $response = $middleware->process($request, $this);

        return $response;
    }

    public function injectMiddleware(array $middleware): void
    {
        array_splice($this->middleware, 0, 0, $middleware);
    }
}