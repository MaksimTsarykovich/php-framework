<?php

declare(strict_types=1);

namespace Tmi\Framework\Http;

class Response
{
    public function __construct(
        private mixed $content = '',
        private int $statusCode = 200,
        private array $headers = []
    ) {
        http_response_code($this->statusCode);
    }

    public function send()
    {
        echo $this->content;
    }

    public function setContent(string $content): Response
    {
        $this->content = $content;
        return $this;
    }


    public function getHeaders(string $key)
    {
        return $this->headers[$key];
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
