<?php

require __DIR__ . '/../vendor/autoload.php';

define('ROOT', __DIR__ . '/../');

$dotenv = \Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

use routes\Router;
use core\Env;

// use Session;

// # Start session with default default or specified configurations.
// var_dump($_SESSION);
// echo '<br>';
// $session = Session::start(); 
// echo 'test';
// var_dump($session);
// echo '<br>';
// var_dump($_SESSION);
// exit;

# Start session with default default or specified configurations.
// try {
//     $session = new Session();
//     var_dump(Session::class);
// } catch (\Throwable $th) {
//     echo $th->getMessage();
//     exit;
// }


$container = new FrameworkX\Container([
    React\Cache\CacheInterface::class => React\Cache\ArrayCache::class,
    Psr\Http\Message\ResponseInterface::class => function () {
        // returns class implementing interface from factory function
        return React\Http\Message\Response::class;
    },
    React\MySQL\ConnectionInterface::class => function () {
        
        $credentials = Env::get('DB_USER').':'.Env::get('DB_PASSWD').'@'.Env::get('DB_HOST').':'.Env::get('DB_PORT').'/'.Env::get('DB_DBNAME').'?idle=0.001&timeout=0.5';
        return (new \React\MySQL\Factory())->createLazyConnection($credentials);
    }
]);

$app = new FrameworkX\App($container);

(new Router($app))->register();

$app->run();
