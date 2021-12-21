<?php

namespace middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DemoMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        // optionally return response without passing to next handler
        // return new React\Http\Message\Response(403, [], "Forbidden!\n");

        // optionally modify request before passing to next handler
        // $request = $request->withAttribute('admin', false);

        // call next handler in chain
        $response = $next($request);
        assert($response instanceof ResponseInterface);

        // optionally modify response before returning to previous handler
        // $response = $response->withHeader('Content-Type', 'text/plain');

        return $response;
    }
}