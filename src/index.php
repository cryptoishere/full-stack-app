<?php

require __DIR__ . '/../vendor/autoload.php';

define('ROOT', __DIR__ . '/../');

$dotenv = \Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

use routes\Router;
use core\Env;
use core\Session;

$container = new FrameworkX\Container([
    React\MySQL\ConnectionInterface::class => function () {
        
        $credentials = Env::get('DB_CREDENTIALS');
        return (new \React\MySQL\Factory())->createLazyConnection($credentials);
    }
]);

$app = new FrameworkX\App($container);

(new Router($app))->register();

$app->run();
