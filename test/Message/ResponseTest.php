<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

use ExtendsFramework\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Message\Response::getBody()
     * @covers \ExtendsFramework\Http\Message\Response::withBody()
     * @covers \ExtendsFramework\Http\Message\Response::getHeaders()
     * @covers \ExtendsFramework\Http\Message\Response::withHeaders()
     * @covers \ExtendsFramework\Http\Message\Response::getStatusCode()
     * @covers \ExtendsFramework\Http\Message\Response::withStatusCode()
     */
    public function testCanCreateNewInstances(): void
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
            ->withStatusCode(200);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame($body, $response->getBody());
        $this->assertSame($headers, $response->getHeaders());
        $this->assertSame(200, $response->getStatusCode());
    }
}
