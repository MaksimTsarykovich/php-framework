<?php

namespace Tmi\Framework\Routing;

class Route
{
    public static function get(string $url, array|callable $handler, array $middleware = []): array
    {
        return ['GET', $url, [$handler, $middleware]];
    }

    public static function post(string $url, array|callable $handler, array $middleware = []): array
    {
        return ['POST', $url, [$handler, $middleware]];
    }
}
