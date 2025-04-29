<?php

namespace Tmi\Framework\Template;

use Tmi\Framework\Session\SessionInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigFactory
{
    public function __construct(
        private string $viewsPath,
        private SessionInterface $session
    )
    {
    }

    public function create():Environment
    {
        $loader = new FilesystemLoader($this->viewsPath);

    }
}