<?php

namespace Tmi\Framework\Template;

use Tmi\Framework\Authentication\SessionAuthInterface;
use Tmi\Framework\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private readonly string               $viewsPath,
        private readonly SessionInterface     $session,
        private readonly SessionAuthInterface $auth,
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
        $twig->addFunction(new TwigFunction('auth',[$this,'getAuth']));

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function getAuth(): SessionAuthInterface
    {
        return $this->auth;
    }
}