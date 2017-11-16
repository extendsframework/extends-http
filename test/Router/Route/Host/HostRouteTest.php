<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Host;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class HostRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that host route can match host and return RouteMatchInterface.
     *
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
        $host = new HostRoute('www.example.com', [
            'foo' => 'bar',
        ]);
        $match = $host->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(5, $match->getPathOffset());
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
        $host = new HostRoute('www.example.net');
        $match = $host->match($request, 5);

        $this->assertNull($match);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Host\HostRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = HostRoute::factory(HostRoute::class, $serviceLocator, [
            'host' => 'www.example.com',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
