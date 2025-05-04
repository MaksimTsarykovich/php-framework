<?php

namespace Tmi\Framework\Http\Middleware;

use Tmi\Framework\Authentication\SessionAuthInterface;
use Tmi\Framework\Http\RedirectResponse;
use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;
use Tmi\Framework\Session\SessionInterface;

class Guest implements MiddlewareInterface
{

    private bool $authenticated = false;

    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface $session
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if ($this->auth->check()) {
        return new RedirectResponse('/dashboard');
        }
        return $handler->handle($request);
    }
}