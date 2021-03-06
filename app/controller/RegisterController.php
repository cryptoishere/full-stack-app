<?php

namespace controller;

use core\Env;
use core\View;

use entities\User;
use BadMethodCallException;

use React\Promise\Deferred;

use Cryptoishere\Bip39\Bip39;

use repository\UserRepository;
use React\Promise\PromiseInterface;

use Cryptoishere\Bip39\Util\Entropy;
use Psr\Http\Message\ServerRequestInterface;


class RegisterController
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

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

            try {
                $this->repository->query("INSERT INTO users (pubkey, username) VALUES (?, ?)", [$pubkey, $address]);
            } catch (\Exception $error) {
                //throw $error;
            }

            return View::json(json_encode(['result' => 'success']));
        }

        return $this->getLoginCredentials()->then(
            function ($newAccountJson) {
                $newAccountJson = json_decode($newAccountJson);
                return View::render('pages/register.html', [
                    'address' => $newAccountJson[0]->address,
                    'pass' => $newAccountJson[0]->pass,
                    'pubkey' => $newAccountJson[0]->pubkey,
                ]);
            },
            function ($reason) {
                // Deferred rejected, do something with $reason
                echo 'failed';
                exit;
            },
        );
    }

    public function getLoginCredentials(): PromiseInterface
    {
        $deferred = new Deferred();

        $url = Env::get('GENERATE_CREDENTIALS');

        $opts = [
            'http' => [
                'method' => "GET",
                'header' => "Content-Type: application/json\r\n",
            ]
        ];

        $context = stream_context_create($opts);
        $fp = fopen($url, 'r', false, $context);

        if (!$fp) {
            $deferred->reject('Server down');
            throw new BadMethodCallException('Server down');
        }

        $deferred->resolve(stream_get_contents($fp));

        fclose($fp);

        return $deferred->promise();
    }
}