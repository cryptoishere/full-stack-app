<?php

namespace controller;

use Psr\Http\Message\ServerRequestInterface;

use core\View;

class LoginController
{
    public function __invoke(ServerRequestInterface $request)
    {
        return View::render('pages/login.html');
    }
}