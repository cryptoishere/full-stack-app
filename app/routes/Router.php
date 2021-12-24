<?php

namespace routes;

use Psr\Http\Message\ServerRequestInterface;
use React\Stream\ThroughStream;

use middleware\AdminMiddleware;
use middleware\ContentTypeTextMiddleware;
use middleware\ContentTypeHTMLMiddleware;
use middleware\AuthMiddleware;
use controller\DashboardController;
use controller\LoginController;
use controller\RegisterController;
use controller\UserController;
use controller\SendController;
use controller\NodeController;

use React\EventLoop\Loop;

use core\View;

use Illuminate\Support\Collection;

class Router
{
    private $app;

    public function __construct(\FrameworkX\App $app) {
        $this->app = $app;
    }

    public function register(): void
    {
        // $app->map(['GET', 'POST'], '/user/{id}', $controller);
        // $app->any('/user/{id}', $controller);

        $this->app->get('/uri[/{path:.*}]', function (ServerRequestInterface $request) {
            return new \React\Http\Message\Response(
                200,
                [
                    'Content-Type' => 'text/plain'
                ],
                (string) $request->getUri() . "\n"
            );
        });

        $this->app->get('/query', function (ServerRequestInterface $request) {
            // Returns a JSON representation of all query params passed to this endpoint.
            // Note that this assumes UTF-8 data in query params and may break for other encodings,
            // see also JSON_INVALID_UTF8_SUBSTITUTE (PHP 7.2+) or JSON_THROW_ON_ERROR (PHP 7.3+)
            return new \React\Http\Message\Response(
                200,
                [
                    'Content-Type' => 'application/json'
                ],
                json_encode((object) $request->getQueryParams(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n"
            );
        });

        $this->app->get('/stream', function (ServerRequestInterface $request) {
            $stream = new ThroughStream();

            $loop = Loop::get();
            $timer = $loop->addPeriodicTimer(0.5, function () use ($stream) {
                $timer = Loop::addPeriodicTimer(0.1, function () use ($stream) {
                    $stream->write(microtime(true) . ' Tick!' . PHP_EOL);
                });
                    
                Loop::addTimer(1.0, function () use ($timer, $stream) {
                    Loop::cancelTimer($timer);
                    $stream->write(microtime(true) . ' Done!' . PHP_EOL);
                });
            });

            $timeout = $loop->addTimer(10.0, function () use ($timer, $loop, $stream) {
                $stream->end();
                $loop->cancelTimer($timer);
            });

            $stream->on('close', function () use ($timer, $timeout, $loop) {
                $loop->cancelTimer($timer);
                $loop->cancelTimer($timeout);
            });

            return new \React\Http\Message\Response(
                200,
                [
                    'Content-Type' => 'text/plain;charset=utf-8'
                ],
                $stream
            );
        });

        $this->app->get('/LICENSE', new \FrameworkX\FilesystemHandler(dirname(__DIR__) . '/LICENSE'));
        $this->app->get('/source/{path:.*}', new \FrameworkX\FilesystemHandler(dirname(__DIR__)));
        $this->app->redirect('/source', '/source/');

        $this->app->any('/method', function (ServerRequestInterface $request) {
            return new \React\Http\Message\Response(
                200,
                [],
                $request->getMethod() . "\n"
            );
        });

        $this->app->get('/etag/', function (ServerRequestInterface $request) {
            $etag = '"_"';
            if ($request->getHeaderLine('If-None-Match') === $etag) {
                return new \React\Http\Message\Response(
                    304,
                    [
                        'ETag' => $etag
                    ],
                    ''
                );
            }

            return new \React\Http\Message\Response(
                200,
                [
                    'ETag' => $etag
                ],
                ''
            );
        });
        $this->app->get('/etag/{etag:[a-z]+}', function (ServerRequestInterface $request) {
            $etag = '"' . $request->getAttribute('etag') . '"';
            if ($request->getHeaderLine('If-None-Match') === $etag) {
                return new \React\Http\Message\Response(
                    304,
                    [
                        'ETag' => $etag,
                        'Content-Length' => strlen($etag) - 1
                    ],
                    ''
                );
            }

            return new \React\Http\Message\Response(
                200,
                [
                    'ETag' => $etag
                ],
                $request->getAttribute('etag') . "\n"
            );
        });

        $this->app->map(['GET', 'POST'], '/headers', function (ServerRequestInterface $request) {
            // Returns a JSON representation of all request headers passed to this endpoint.
            // Note that this assumes UTF-8 data in request headers and may break for other encodings,
            // see also JSON_INVALID_UTF8_SUBSTITUTE (PHP 7.2+) or JSON_THROW_ON_ERROR (PHP 7.3+)
            return new \React\Http\Message\Response(
                200,
                [
                    'Content-Type' => 'application/json'
                ],
                json_encode(
                    (object) array_map(function (array $headers) { return implode(', ', $headers); }, $request->getHeaders()),
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES
                ) . "\n"
            );
        });

        $this->app->get('/error', function () {
            throw new \RuntimeException('Unable to load error');
        });
        $this->app->get('/error/null', function () {
            return null;
        });
        $this->app->get('/error/yield', function () {
            yield null;
        });

        // OPTIONS *
        $this->app->options('', function () {
            return new \React\Http\Message\Response(200);
        });

        // PRODUCTION
        $this->app->get('/', AuthMiddleware::class, DashboardController::class);

        $this->app->get('/login', LoginController::class);
        $this->app->map(['GET', 'POST'], '/register', RegisterController::class);

        $this->app->redirect('/promo/reactphp', 'http://reactphp.org/');
        $this->app->redirect('/blog.html', '/blog', 301);

        $this->app->get('/user', AuthMiddleware::class, UserController::class);
        $this->app->get('/user/{id:[0-9]+}', AuthMiddleware::class, UserController::class);

        $this->app->get('/send', AuthMiddleware::class, AdminMiddleware::class, ContentTypeHTMLMiddleware::class, SendController::class);

        $this->app->post('/transaction', AuthMiddleware::class, function (ServerRequestInterface $request) {
            $url1 = 'http://185.185.127.77:6100/api/transactions';

            $trans = json_encode([
                "recipientId" => "ShWXjqeNbm7Cd7onwC6eXT6e3jnejzZdDb",
                "amount" => 100000000,
                "secret" => "shell beach stairs program spot jelly victory nuclear confirm worry salt deputy",
            ]);

            $opts = [
                'http' => [
                    'method' => "PUT",
                    'header' => "Content-Type: application/json\r\n" . "nethash: fc46bfaf9379121dd6b09f5014595c7b7bd52a0a6d57c5aff790b42a73c76da7\r\n" . "version: 0.0.2\r\n",
                    'content' => $trans,
                ]
            ];

            $context = stream_context_create($opts);
            $fp = fopen($url1, 'r', false, $context);
            $transaction = stream_get_contents($fp);
            fclose($fp);

            return View::json(
                $transaction,
                ['Content-Type' => 'application/json'],
            );
        });

        $this->app->get('/getBalance', AuthMiddleware::class, function (ServerRequestInterface $request) {
            $url = 'http://livedev.info:6100/api/accounts/getBalance?address=SefFut3o9aTRXbXCemcAahxBVTrKpzt1VB';
            $fp = fopen($url, 'r');
            $balance = stream_get_contents($fp);
            fclose($fp);

            return View::json(
                $balance,
                ['Content-Type' => 'application/json'],
            );
        });

        $this->app->get('/node', AuthMiddleware::class, NodeController::class);

        $this->app->get('/debug', function (ServerRequestInterface $request) {
            ob_start();
            var_dump($request);
            $info = ob_get_clean();

            // if (PHP_SAPI !== 'cli' && (!function_exists('xdebug_is_enabled') || !\xdebug_is_enabled())) {
            if (PHP_SAPI !== 'cli' && (!function_exists('xdebug_is_enabled'))) {
            
                $info = htmlspecialchars($info, 0, 'utf-8');
            }

            return new \React\Http\Message\Response(
                200,
                [
                    'Content-Type' => 'text/html;charset=utf-8'
                ],
                '<h2>Request</h2><pre>' . $info . '</pre>' . "\n"
            );
        });
    }
}