<?php

declare(strict_types=1);

namespace Tmi\Framework\Http;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\DsnParser;
use League\Container\Container;
use Tmi\Framework\Http\Exceptions\HttpException;
use Tmi\Framework\Routing\Router;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private Router $router,
        private Container $container
    ) {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request)
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response;
    }

    private function createExceptionResponse(\Exception $e)
    {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }
        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Server Error', 500);
    }
}
