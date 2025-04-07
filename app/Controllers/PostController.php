<?php

namespace App\Controllers;

use Tmi\Framework\Controller\AbstractController;
use \Tmi\Framework\Http\Response;

class PostController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->render('post.html.twig', [
            'postId' => $id
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }
}
