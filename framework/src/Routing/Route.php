<?php

namespace Tmi\Framework\Routing;

class Route
{
    public static function get(string $url, array|callable $handler): array
    {
        return ['GET', $url, $handler];
    }

    public static function post(string $url, array|callable $handler): array
    {
        return ['POST', $url, $handler];
    }
}
