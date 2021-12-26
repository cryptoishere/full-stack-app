<?php

namespace controller;

use BadMethodCallException;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;


use repository\UserRepository;
use entities\UserLogin;
use \core\Env;

use React\Http\Browser;
use Psr\Http\Message\ResponseInterface;

use core\View;
use core\Auth;
use core\Session;

class LoginController
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($data = $request->getParsedBody()) {
            $pass = $data['passphrase'] ?? '';
            
            return (new Browser())->post(
                Env::get('LOGIN_AUTH_URL'),
                ['Content-Type' => 'application/x-www-form-urlencoded'],
                http_build_query(['passphrase' => trim($pass)])
            )->then(function (ResponseInterface $response) {
                // var_dump($response->getHeaders(), (string)$response->getBody());
                $json = json_decode($response->getBody());

                $handler = function (?UserLogin $user) use ($json) {
                    if ($user === null) {
                        return View::json(json_encode(['result' => 'failed']), [], 404);
                    }

                    if ($json[0]->pubkey === $user->pubkey) {
                        Session::init(Session::SESSION_LIFETIME);
                        // Pass Match
                        // TODO: fix return of UPDDATE query
                        if (!$sessionSaved = Auth::setSessionId($json[0]->address)) {
                            return View::json(json_encode(['result' => 'failed']), [], 404);
                        }

                        Session::set('user_authenticated', (string)$json[0]->address);

                        return View::json(json_encode(['result' => 'success']), [], 200);
                    }
                };

                return $this->repository->findUserByAddress((string)$json[0]->address)->then($handler);

                // return View::json(json_encode(['test' => $json[0]->address]));
            });
        }

        return View::render('pages/login.html');
    }
}