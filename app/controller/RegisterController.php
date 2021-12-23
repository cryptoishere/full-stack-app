<?php

namespace controller;

use Psr\Http\Message\ServerRequestInterface;

use Cryptoishere\Bip39\Bip39;
use Cryptoishere\Bip39\Util\Entropy;

use core\View;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;


class RegisterController
{
    public function __invoke(ServerRequestInterface $request)
    {
        // the parameter here is the size, in bits, of the random data to be generated.
        // values can be between 128 and 256, and must be multiples of 32.
        $newEntropy = Entropy::random(128);
        $newEntropy = new Entropy($newEntropy);
        $bip39 = new Bip39('en');

        if ($data = $request->getParsedBody()) {
            $pubkey = $data['pubkey'] ?? '';
            $address = $data['address'] ?? '';

            // Save to database;

            return View::json(
                json_encode(['result' => 'success']),
                ['Content-Type' => 'application/json'],
            );
        }

        $promise = $this->getAwesomeResultPromise();

        $result = $promise->then(
            function ($reg) {
                $array = json_decode($reg);
                // var_dump($array[0]);
                // exit;
                // Deferred resolved, do something with $value
                return View::render('pages/register.html', [
                    'address' => $array[0]->address,
                    'pass' => $array[0]->pass,
                    'pubkey' => $array[0]->pubkey,
        
                ]);
            },
            function ($reason) {
                // Deferred rejected, do something with $reason
                echo 'failed';
                exit;
            },
            function ($update) {
                // Progress notification triggered, do something with $update
                echo 'update';
                exit;
            }
        );

        return $result;
    }

    public function getAwesomeResultPromise(): PromiseInterface
    {
        $deferred = new Deferred();

        $url = 'http://localhost:3000/api/mainnet/1';

        $opts = [
            'http' => [
                'method' => "GET",
                'header' => "Content-Type: application/json\r\n",
            ]
        ];

        $context = stream_context_create($opts);
        $fp = fopen($url, 'r', false, $context);

        if (!$newAccountJson = stream_get_contents($fp)) {
            $deferred->reject('Server down');
        } else {
            $deferred->resolve($newAccountJson);
        }

        fclose($fp);

        // Return the promise
        return $deferred->promise();
    }
}