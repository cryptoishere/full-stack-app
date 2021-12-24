<?php

namespace core;

use \React\Http\Message\Response;
use \core\Env;
use RuntimeException;

class View
{
    public static function render(string $page, array $data = [], array $headers = [], int $statusCode = 200): Response
    {
        $loader = new \Twig\Loader\FilesystemLoader(Env::get('VIEW_PATH') ?: []);

        if (!$templateCache = Env::get('TWIG_CACHE')) {
            throw new RuntimeException('Twig cache path empty.');
        }

        $twig = new \Twig\Environment($loader, [
            // TODO: Disable cache on development for hot reload. Need fix.
            // 'cache' => $templateCache,
            'debug' => true,
        ]);

        $includeAssests = new \Twig\TwigFunction('webpack_inc', function (string $filename) {
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
}