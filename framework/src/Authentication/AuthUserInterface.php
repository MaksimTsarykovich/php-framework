<?php

namespace Tmi\Framework\Authentication;

interface AuthUserInterface
{
    public function getId(): int;

    public function getEmail(): string;

    public function getPassword(): string;

}