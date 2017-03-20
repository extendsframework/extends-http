<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

use ExtendsFramework\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Response\Response::__construct()
     * @covers \ExtendsFramework\Http\Response\Response::getBody()
     * @covers \ExtendsFramework\Http\Response\Response::getHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::getStatusCode()
     */
    public function testCanCreateNewInstance(): void
    {
        $response = new Response();

        $this->assertInstanceOf(ContainerInterface::class, $response->getBody());
        $this->assertInstanceOf(ContainerInterface::class, $response->getHeaders());
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @covers  \ExtendsFramework\Http\Response\Response::withHeader()
     */
    public function testCanCreateNewInstanceWithHeaders(): void
    {
        $response = new Response();
        $clone = $response->withHeader('foo', 'bar');

        $this->assertNotSame($clone, $response);
        $this->assertSame('bar', $response->getHeaders()->get('foo'));
    }
}
