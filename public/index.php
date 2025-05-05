<?php


use Tmi\Framework\Http\Kernel;
use Tmi\Framework\Http\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$request = Request::createFromGlobals();

$content = 'Hello World!';

/** @var League\Container\Container $container */
$container = require BASE_PATH . '/config/services.php';

require BASE_PATH . '/bootstrap/bootstrap.php';

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);

