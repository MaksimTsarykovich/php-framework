<?php

namespace App\Controllers;

use Tmi\Framework\Controller\AbstractController;
use Tmi\Framework\Http\Response;

class DashboardController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }
}