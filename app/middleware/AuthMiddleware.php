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
        // if (!self::initAndCheckAuthentication()) {
        //     return View::redirect(301, [
        //         'Location' => Env::get('URL') . 'login?redirect=' . urlencode($_SERVER['REQUEST_URI']),
        //     ]);
        // }

        $response = $next($request);

        return $response;
    }

    public static function initAndCheckAuthentication(): bool
    {
        Session::init(1);

        self::checkSessionConcurrency();

        if (!Session::userIsLoggedIn()) {

            Session::destroy();

            return false;
        }

        return true;
    }

    public static function checkSessionConcurrency()
    {
        if (Session::userIsLoggedIn()) {
            if (Session::isConcurrentSessionExists()) {

                // var_dump('session_id and db save id not matching, some one esle can be used other session');
                // exit;
            }
        }
    }
}