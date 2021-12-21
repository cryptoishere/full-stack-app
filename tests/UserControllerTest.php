<?php

namespace Acme\Tests\Todo;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\ServerRequest;

class UserControllerTest extends TestCase
{
    public function testControllerReturnsValidResponse()
    {
        $request = new ServerRequest('GET', 'http://example.com/users/Alice');
        $request = $request->withAttribute('name', 'Alice');

        $controller = new \controller\UserController();
        $response = $controller($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Hello Alice!\n", (string) $response->getBody());
    }
}