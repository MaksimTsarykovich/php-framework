<?php

use Tmi\Framework\Event\EventDispatcher;
use Tmi\Framework\Http\Kernel;
use Tmi\Framework\Http\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$request = Request::createFromGlobals();

$content = 'Hello World!';

/** @var League\Container\Container $container */
$container = require BASE_PATH . '/config/services.php';

$eventDispatcher = $container->get(EventDispatcher::class);
$eventDispatcher
    ->addListener(
        \Tmi\Framework\Http\Events\ResponseEvent::class,
        new \App\Listeners\InternalErrorListener()
    )
    ->addListener(
        \Tmi\Framework\Http\Events\ResponseEvent::class,
        new \App\Listeners\ContentLengthListener()
    )
    ->addListener(
        \Tmi\Framework\Dbal\Event\EntityPersist::class,
        new \App\Listeners\HandleEntityListener()
    );

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);

