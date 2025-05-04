<?php

namespace App\Services;

use App\Entities\User;
use Doctrine\DBAL\Connection;
use Tmi\Framework\Authentication\AuthUserInterface;
use Tmi\Framework\Authentication\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private Connection $connection,
    )
    {
    }

    public function store(User $user): User
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->insert('users')
            ->values([
                'name' => ':name',
                'email' => ':email',
                'password' => ':password',
                'created_at' => ':created_at',

            ])
            ->setParameters([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            ])
            ->executeQuery();
        $id = $this->connection->lastInsertId();

        $user->setId($id);

        return $user;
    }

    public function findByEmail(string $email): ?AuthUserInterface
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery()
        ;
        $user = $result->fetchAssociative();
        if (!$user) {
            return null;
        }
        return user::create(
            email: $user['email'],
            password: $user['password'],
            createdAt: new \DateTimeImmutable($user['created_at']),
            name: $user['name'],
            id: $user['id'],
        );
    }
}