<?php

namespace middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ContentTypeTextMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $response = $next($request);
        assert($response instanceof ResponseInterface);

        return $response->withHeader('Content-Type', 'text/plain');
    }
}