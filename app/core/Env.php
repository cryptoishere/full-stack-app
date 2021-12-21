<?php

namespace core;

use \React\Http\Message\Response;

class Env
{
    public static function get(string $name): ?string
    {
        return $_ENV[$name] ?: null;
    }
}