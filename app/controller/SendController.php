<?php

namespace controller;

use Psr\Http\Message\ServerRequestInterface;

use core\View;

class SendController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $data['role'] = $request->getAttribute('admin') ? 'admin' : 'user';
        $data['name'] = 'Developer';

        return View::render('pages/transaction/send.html', $data);
    }
}