<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Host;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class HostRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that host route can match host and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::match()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getHost')
            ->willReturn('www.example.com');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $host = HostRoute::factory([
            'host' => 'www.example.com',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);
        $match = $host->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(0, $match->getPathOffset());
            $this->assertSame([
                'foo' => 'bar',
            ], $match->getParameters());
        }
    }

    /**
     * No match.
     *
     * Test that host route can not match host and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::match()
     */
    public function testNoMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getHost')
            ->willReturn('www.example.com');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $host = HostRoute::factory([
            'host' => 'www.example.net',
        ]);
        $match = $host->match($request, 5);

        $this->assertNull($match);
    }

    /**
     * Missing host.
     *
     * Test that factory will throw an exception for missing host in options.
     *
     * @covers                   \ExtendsFramework\Http\Router\Route\Host\HostRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Host\Exception\MissingHost::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Host\Exception\MissingHost
     * @expectedExceptionMessage Host is required and must be set in options.
     */
    public function tesMissingHost(): void
    {
        HostRoute::factory([]);
    }
}
