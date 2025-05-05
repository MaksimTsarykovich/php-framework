<?php

declare(strict_types=1);

namespace Tmi\Framework\Http;

use League\Container\Container;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tmi\Framework\Http\Events\ResponseEvent;
use Tmi\Framework\Http\Exceptions\HttpException;
use Tmi\Framework\Http\Middleware\RequestHandlerInterface;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private readonly Container                $container,
        private readonly RequestHandlerInterface  $requestHandler,
        private readonly EventDispatcherInterface $eventDispatcher,
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

        $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));

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
