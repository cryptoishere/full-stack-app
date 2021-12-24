<?php

namespace core;

use \React\Http\Message\Response;
use \core\Env;
use \Twig\TwigFunction;
use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;
use RuntimeException;

class View
{
    public static function render(string $page, array $data = [], array $headers = [], int $statusCode = 200): Response
    {
        $loader = new FilesystemLoader(Env::get('VIEW_PATH') ?: []);

        if (!$templateCache = Env::get('TWIG_CACHE')) {
            throw new RuntimeException('Twig cache path empty.');
        }

        $twig = new Environment($loader, [
            // TODO: Disable cache on development for hot reload. Need fix.
            // 'cache' => $templateCache,
            'debug' => true,
        ]);

        $twig->addGlobal('URL', Env::get('URL'));

        $includeAssests = new TwigFunction('webpack_inc', function (string $filename) {
            if ($manifestJson = file_get_contents('/home/crypto/Desktop/www/html/public/manifest.json')) {
                $decodedJson = json_decode($manifestJson);

                return (string) $decodedJson->{$filename};
            }

            return $filename;
        });

        $twig->addFunction($includeAssests);

        return new Response(
            $statusCode,
            $headers,
            $twig->render($page, $data),
        );
    }

    public static function json($json, array $headers = [], int $statusCode = 200): Response
    {
        return new Response(
            $statusCode,
            $headers,
            $json,
        );
    }

    public static function redirect(int $statusCode, array $headers): Response
    {   
        return new Response(
            $statusCode,
            $headers,
        );
    }
}