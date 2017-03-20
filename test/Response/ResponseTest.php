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
     * @return ResponseInterface
     */
    public function testCanCreateNewInstance(): ResponseInterface
    {
        $body = $this->createMock(ContainerInterface::class);

        $headers = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $body
         * @var ContainerInterface $headers
         */
        $response = new Response($body, $headers, 200);

        $this->assertSame($body, $response->getBody());
        $this->assertSame($headers, $response->getHeaders());
        $this->assertSame(200, $response->getStatusCode());

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @depends testCanCreateNewInstances
     * @covers  \ExtendsFramework\Http\Response\Response::withHeader()
     */
    public function testCanCreateNewInstanceWithHeaders(ResponseInterface $response): void
    {
        $clone = $response->withHeader('foo', 'bar');

        $this->assertNotSame($clone, $response);
        $this->assertSame('bar', $response->getHeaders()->get('foo'));
    }
}
