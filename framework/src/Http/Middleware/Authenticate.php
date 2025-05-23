<?php

namespace Tmi\Framework\Http\Middleware;

use Tmi\Framework\Authentication\SessionAuthInterface;
use Tmi\Framework\Http\RedirectResponse;
use Tmi\Framework\Http\Request;
use Tmi\Framework\Http\Response;
use Tmi\Framework\Session\SessionInterface;

class Authenticate implements MiddlewareInterface
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

        if (!$this->auth->check()) {
            $this->session->setFlash('error','Чтобы продолжить, нужно войти');
        return new RedirectResponse('/login');
        }
        return $handler->handle($request);
    }
}