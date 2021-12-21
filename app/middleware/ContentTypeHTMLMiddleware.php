<?php

namespace middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ContentTypeHTMLMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $response = $next($request);
        assert($response instanceof ResponseInterface);

        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}