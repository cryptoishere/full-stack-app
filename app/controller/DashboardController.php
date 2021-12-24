<?php

namespace controller;

use Psr\Http\Message\ServerRequestInterface;

use repository\UserRepository;
use entities\User;

use core\View;

// TODO: import
// use function \React\Async\await;

class DashboardController
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = 6;
        // $id = $request->getAttribute('id');
        

        if (!$id) {
            return View::render('pages/user/index.html', ['name' => 'Developer'], [], 200);
        }

        return $this->repository->findUser($id)->then(function (?User $user) {
            if ($user === null) {
                return View::render('errors/404.html', ['error' => "User not found\n"], [], 404);
            }

            return View::render('pages/dashboard/dashboard.html', ['username' => $user->username, 'pin' => $user->pin], [], 200);
        });
    }
}

