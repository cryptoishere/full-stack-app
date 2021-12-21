<?php

namespace middleware;

use Psr\Http\Message\ServerRequestInterface;

class AdminMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $ip = $request->getServerParams()['REMOTE_ADDR'];
        if ($ip === '127.0.0.1') {
            $request = $request->withAttribute('admin', true);
        }

        return $next($request);
    }
}