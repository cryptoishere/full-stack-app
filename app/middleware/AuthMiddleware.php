<?php

namespace middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use core\Env;
use core\Session;
use core\View;

class AuthMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if (!self::checkAuthentication()) {
            return View::redirect(301, [
                'Location' => Env::get('URL') . 'login?redirect=' . urlencode($_SERVER['REQUEST_URI']),
            ]);
        }

        $response = $next($request);

        return $response;
    }

    // public static function initAndCheckAuthentication(): bool
    public static function checkAuthentication(): bool
    {
        Session::init(Session::SESSION_LIFETIME);

        if (!Session::userIsLoggedIn() || !self::checkSessionConcurrency()) {
            Session::destroy();

            return false;
        }

        return true;
    }

    public static function checkSessionConcurrency(): bool
    {
        if (Session::userIsLoggedIn() && Session::isConcurrentSessionExists()) {
            return false;
        }

        return true;
    }
}