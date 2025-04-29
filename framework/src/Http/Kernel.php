<?php

declare(strict_types=1);

namespace Tmi\Framework\Http;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\DsnParser;
use League\Container\Container;
use Tmi\Framework\Http\Exceptions\HttpException;
use Tmi\Framework\Http\Middleware\RequestHandlerInterface;
use Tmi\Framework\Routing\Router;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private Router                  $router,
        private Container               $container,
        private RequestHandlerInterface $requestHandler,
    )
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request)
    {
        try {
            $response = $this->requestHandler->handle($request);

        } catch (\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response;
    }

    public function terminate(Request $request,Response $response):void
    {
        $request->getSession()?->clearFlash();
    }

    private function createExceptionResponse(\Exception $e)
    {
        if (in_array($this->appEnv, ['local',
            'testing'])) {
            throw $e;
        }
        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Server Error', 500);
    }

}
