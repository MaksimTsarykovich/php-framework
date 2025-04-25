<?php

namespace App\Services;

use App\Entities\Post;
use Doctrine\DBAL\Connection;
use Tmi\Framework\Http\Exceptions\NotFoundException;

class PostService
{
    public function __construct(
        private Connection $connection,
    )
    {
    }

    public function save(Post $post): Post
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->insert('posts')
            ->values([
                'title' => ':title',
                'content' => ':content',
                'created_at' => ':created_at',

            ])
            ->setParameters([
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            ])
            ->executeQuery();
        $id = $this->connection->lastInsertId();

        $post->setId($id);

        return $post;
    }

    public function find(int $id): ?Post
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ;
        $post = $result->fetchAssociative();
        if (!$post) {
            return null;
        }
        return Post::create(
            title: $post['title'],
            content: $post['content'],
            id: $post['id'],
            createdAt: new \DateTimeImmutable($post['created_at']),
        );

    }

    public function findOrFail(int $id): Post
    {
        $post = $this->find($id);
        if (is_null($post)) {
            throw new NotFoundException("Post $id not found");
        }
        return $post;
    }
}