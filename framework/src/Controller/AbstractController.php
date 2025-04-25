<?php

namespace Tmi\Framework\Controller;

use Psr\Container\ContainerInterface;
use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;
    protected ?Request $request = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function setRequest(?Request $request): AbstractController
    {
        $this->request = $request;
        return $this;
    }

    public function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $content = $this->container->get('twig')->render($view, $parameters);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }
}