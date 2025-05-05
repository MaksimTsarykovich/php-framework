<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Tmi\Framework\Controller\AbstractController;
use Tmi\Framework\Http\RedirectResponse;
use Tmi\Framework\Http\Response;
use Tmi\Framework\Session\SessionInterface;

class PostController extends AbstractController
{

    public function __construct(
        private PostService $service,
    )
    {
    }

    public function show(int $id): Response
    {
        $post = $this->service->findOrFail($id);
        return $this->render('post.html.twig', [
            'post' => $post
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }

    public function store()
    {
        $post = Post::create(
            $this->request->input('title'),
            $this->request->input('content')
        );

        $post = $this->service->save($post);

        $this->request->getSession()->setFlash('success','Пост успешно создан!');

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
