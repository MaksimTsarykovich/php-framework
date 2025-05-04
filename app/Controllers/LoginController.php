<?php

namespace App\Controllers;

use Tmi\Framework\Authentication\SessionAuthInterface;
use Tmi\Framework\Controller\AbstractController;
use Tmi\Framework\Http\RedirectResponse;
use Tmi\Framework\Http\Response;

class LoginController extends AbstractController
{

    public function __construct(
        private readonly SessionAuthInterface $auth,
    )
    {
    }

    public function form(): Response
    {
        return $this->render('login.html.twig');
    }

    public function login()
    {
        $isAuth = $this->auth->authenticate(
            $this->request->input('email'),
            $this->request->input('password')
        );

        if (!$isAuth) {
           $this->request->getSession()->setFlash('error','Неверный логин или пароль');

           return new RedirectResponse('/login');
        }

        $this->request->getSession()->setFlash('success','Вход выполнен успешно');

        return new RedirectResponse('/dashboard');
    }

    public function logout(): RedirectResponse
    {
        $this->auth->logout();

        return new RedirectResponse('/login');
    }
}