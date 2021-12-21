<?php

require __DIR__ . '/../vendor/autoload.php';

define('ROOT', __DIR__ . '/../');

$dotenv = \Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

use routes\Router;
use core\Env;

$container = new FrameworkX\Container([
    React\MySQL\ConnectionInterface::class => function () {
        
        $credentials = Env::get('DB_USER').':'.Env::get('DB_PASSWD').'@'.Env::get('DB_HOST').':'.Env::get('DB_PORT').'/'.Env::get('DB_DBNAME').'?idle=0.001&timeout=0.5';
        return (new \React\MySQL\Factory())->createLazyConnection($credentials);
    }
]);

$app = new FrameworkX\App($container);

(new Router($app))->register();

$app->run();
