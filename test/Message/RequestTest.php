<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

use ExtendsFramework\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Message\Request::getBody()
     * @covers \ExtendsFramework\Http\Message\Request::withBody()
     * @covers \ExtendsFramework\Http\Message\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Message\Request::withHeaders()
     * @covers \ExtendsFramework\Http\Message\Request::getMethod()
     * @covers \ExtendsFramework\Http\Message\Request::withMethod()
     * @covers \ExtendsFramework\Http\Message\Request::getParameters()
     * @covers \ExtendsFramework\Http\Message\Request::withParameters()
     * @covers \ExtendsFramework\Http\Message\Request::getPath()
     * @covers \ExtendsFramework\Http\Message\Request::withPath()
     */
    public function testCanCreateNewInstances(): void
    {
        $body = $this->createMock(ContainerInterface::class);

        $headers = $this->createMock(ContainerInterface::class);

        $parameters = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $body
         * @var ContainerInterface $headers
         * @var ContainerInterface $parameters
         */
        $request = (new Request())
            ->withBody($body)
            ->withHeaders($headers)
            ->withMethod('GET')
            ->withParameters($parameters)
            ->withPath('/foo/bar');

        $this->assertSame($request->getBody(), $body);
        $this->assertSame($request->getHeaders(), $headers);
        $this->assertSame($request->getMethod(), 'GET');
        $this->assertSame($request->getParameters(), $parameters);
        $this->assertSame($request->getPath(), '/foo/bar');
    }
}
