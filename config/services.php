<?php

use App\Services\UserService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\DsnParser;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Tmi\Framework\Authentication\SessionAuthentication;
use Tmi\Framework\Authentication\SessionAuthInterface;
use Tmi\Framework\Console\Application;
use Tmi\Framework\Console\Commands\MigrateCommand;
use Tmi\Framework\Controller\AbstractController;
use Tmi\Framework\Dbal\ConnectionFactory;
use Tmi\Framework\Event\EventDispatcher;
use Tmi\Framework\Http\Kernel;
use Tmi\Framework\Http\Middleware\ExtractRouteInfo;
use Tmi\Framework\Http\Middleware\RequestHandler;
use Tmi\Framework\Http\Middleware\RequestHandlerInterface;
use Tmi\Framework\Http\Middleware\RouterDispatch;
use Tmi\Framework\Routing\RouteInterface;
use Tmi\Framework\Routing\Router;
use Tmi\Framework\Session\Session;
use Tmi\Framework\Session\SessionInterface;
use Tmi\Framework\Template\TwigFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Tmi\Framework\Console\Kernel as ConsoleKernel;

$dotenv = new Dotenv;
$dotenv->load(BASE_PATH . '/.env');
// Application parameters

$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewsPath = BASE_PATH . '/views';
$databaseUrl = 'pdo-mysql://user:password@framework-db:3306/app';

// Application services

$container = new Container;

$container->delegate(new ReflectionContainer(true));

$container->add('framework-commands-namespace', new StringArgument('Tmi\\Framework\\Console\\Commands\\'));

$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouteInterface::class, Router::class);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->addShared(EventDispatcher::class);

$container->add(Kernel::class)
    ->addArguments([
        $container,
        RequestHandlerInterface::class,
        EventDispatcher::class
    ]);


$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
    ->addArguments([
        new StringArgument($viewsPath),
        SessionInterface::class,
        SessionAuthInterface::class,
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->addShared('connectionParams', function () use ($container, $databaseUrl) {
    return $container->get(DsnParser::class)->parse($databaseUrl);
});

$container->add(ConnectionFactory::class)
    ->addArgument(new ArrayArgument($container->get('connectionParams')));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add(Application::class)
    ->addArgument($container);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));

$container->add(RouterDispatch::class)
    ->addArguments([
        RouteInterface::class,
        $container,
    ]);

$container->add(SessionAuthInterface::class, SessionAuthentication::class)
    ->addArguments([
        UserService::class,
        SessionInterface::class
    ]);

$container->add(ExtractRouteInfo::class)
    ->addArgument(new ArrayArgument($routes));


return $container;
