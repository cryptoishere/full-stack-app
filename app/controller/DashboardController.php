<?php

namespace controller;

use Psr\Http\Message\ServerRequestInterface;

use core\View;

// TODO: import
// use function \React\Async\await;

class DashboardController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $credentials = 'root:root@127.0.0.1:53066/dev_test?idle=0.001&timeout=0.5';
        $db = (new \React\MySQL\Factory())->createLazyConnection($credentials);

        $id = 6;

        return $db->query(
            'SELECT id FROM users WHERE id = ?', [$id]
        )->then(function (\React\MySQL\QueryResult $result) {
            if (count($result->resultRows) === 0) {
                return View::render('errors/404.html', ['error' => "Not found\n"], [], 404);
            }

            return View::render('pages/dashboard/dashboard.html', $result->resultRows[0]);
        });
    }
}

