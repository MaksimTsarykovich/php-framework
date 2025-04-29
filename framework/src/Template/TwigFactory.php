<?php

namespace Tmi\Framework\Template;

use Tmi\Framework\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private string           $viewsPath,
        private SessionInterface $session
    )
    {
    }

    public function create(): Environment
    {
        $loader = new FilesystemLoader($this->viewsPath);

        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session',[$this,'getSession']));

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}