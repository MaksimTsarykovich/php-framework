<?php

namespace App\Controllers;

use Tmi\Framework\Controller\AbstractController;
use Tmi\Framework\Http\Response;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct()
    {

    }

    public function index()
    {
        return $this->render('home.html.twig',[
            'name' => 'John Doe'
        ]);
    }
}
