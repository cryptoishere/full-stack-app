<?php

namespace controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;

use core\View;
use repository\UserRepository;
use entities\User;

class UserController
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return PromiseInterface<ResponseInterface> **/
    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');

        if (!$id) {
            return View::render('pages/user/index.html', ['name' => 'Developer'], [], 200);
        }

        return $this->repository->findUser($id)->then(function (?User $user) {
            if ($user === null) {
                return View::render('errors/404.html', ['error' => "User not found\n"], [], 404);
            }

            return View::render('pages/user/user.html', ['id' => $user->id, 'name' => $user->username], [], 200);
        });
    }
}