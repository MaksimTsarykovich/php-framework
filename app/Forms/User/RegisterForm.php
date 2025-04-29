<?php

namespace App\Forms\User;

use App\Entities\User;
use App\Services\UserService;

class RegisterForm
{
    private ?string $name;

    private string $email;

    private string $password;

    private string $passwordConfirmation;

    public function __construct(
        private UserService $userService
    )
    {
    }

    public function setFields(string $email, string $password, string $passwordConfirmation, ?string $name = null): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function save():User
    {
        $user = User::create(
            $this->email,
            password_hash($this->password, PASSWORD_DEFAULT),
            new \DateTimeImmutable(),
            $this->name
        );

        $user = $this->userService->store($user);

        return $user;
    }

    public function getValidationErrors(): array
    {
        $error =[];
        
        if (!empty($this->name) && strlen($this->name) > 50) {
            $error[] = 'Максимальная длинна имени 50 символов';
        }
        
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Неверный формат электронной почты';
        }
        
        if (empty($this->password) || strlen($this->password) < 8) {
            $error[] = 'Минимальная длинна пароля 8 символов';
        }
        
        if ($this->password !== $this->passwordConfirmation) {
            $error[] = 'Пароли не совпадают';
        }
        
        return $error;
    }

    public function hasValidationErrors(): bool
    {
        return !empty($this->getValidationErrors());
    }


}