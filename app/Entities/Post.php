<?php

namespace App\Entities;

use Tmi\Framework\Dbal\Entity;

class Post extends Entity
{
    public function __construct(
        private ?int                $id,
        private string              $title,
        private string              $content,
        private ?\DateTimeImmutable $createdAt,
    )
    {
    }

    public static function create(
        string $title,
        string $content,
        ?int $id = null,
        \DateTimeImmutable $createdAt = null
    ): static
    {
        return new static($id, $title, $content, $createdAt ?? new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


}