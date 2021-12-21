<?php

namespace controller;

use Psr\Http\Message\ServerRequestInterface;

use core\View;

class NodeController
{
    public function __invoke(ServerRequestInterface $request)
    {
        $data['name'] = 'Developer';

        return View::render('pages/node/node.html', $data);
    }
}