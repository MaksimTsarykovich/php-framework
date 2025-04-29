<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Tmi\Framework\Controller\AbstractController;
use Tmi\Framework\Http\RedirectResponse;
use Tmi\Framework\Http\Response;

class RegisterController extends AbstractController
{

    public function __construct(
        private UserService $userService
    )
    {
    }

    public function form(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register()
    {
        $form = new RegisterForm($this->userService);

        $form->setFields(
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('confirm_password'),
            $this->request->input('name'),
        );

        if($form->hasValidationErrors()){
            foreach($form->getValidationErrors() as $error){
                $this->request->getSession()->setFlash('error', $error);
            }
            return new RedirectResponse('/register');
        }

        $user = $form->save();

        $this->request->getSession()->setFlash('success',"Пользователь {$user->getEmail()} успешно зарегестрирован");

        return new RedirectResponse('/register');
    }
}