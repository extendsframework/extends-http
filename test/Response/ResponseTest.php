<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

use ExtendsFramework\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
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
     * @covers  \ExtendsFramework\Http\Response\Response::withHeaders()
     * @covers  \ExtendsFramework\Http\Response\Response::andHeader()
     * @covers  \ExtendsFramework\Http\Response\Response::getHeaders()
     */
    public function testCanCreateNewInstanceWithHeaders(): void
    {
        $headers = $this->createMock(ContainerInterface::class);
        $headers
            ->expects($this->once())
            ->method('with')
            ->with('foo', 'bar')
            ->willReturnSelf();

        /**
         * @var ContainerInterface $headers
         */
        $response = (new Response())
            ->withHeaders($headers)
            ->andHeader('foo', 'bar');

        $this->assertSame($headers, $response->getHeaders());
    }

    /**
     * @covers \ExtendsFramework\Http\Response\Response::withBody()
     * @covers \ExtendsFramework\Http\Response\Response::withHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::withStatusCode()
     * @covers \ExtendsFramework\Http\Response\Response::getBody()
     * @covers \ExtendsFramework\Http\Response\Response::getHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::getStatusCode()
     */
    public function testCanGetFromWithMethods(): void
    {
        $body = $this->createMock(ContainerInterface::class);

        $headers = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $body
         * @var ContainerInterface $headers
         */
        $response = (new Response())
            ->withBody($body)
            ->withHeaders($headers)
            ->withStatusCode(201);

        $this->assertSame($body, $response->getBody());
        $this->assertSame($headers, $response->getHeaders());
        $this->assertSame(201, $response->getStatusCode());
    }
}
