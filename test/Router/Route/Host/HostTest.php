<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Host;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Host\Host::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Host\Host::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Host\Host::match()
     */
    public function testCanMatchSegment(): void
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
        $host = Host::factory([
            'host' => 'www.example.com',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);
        $match = $host->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
        ], $match->getParameters()->extract());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Host\Host::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Host\Host::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Host\Host::match()
     */
    public function testCanNotMatchSegment(): void
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
        $host = Host::factory([
            'host' => 'www.example.net',
        ]);
        $match = $host->match($request, 5);

        $this->assertNull($match);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Host\Host::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Host\Exception\InvalidOptions::forMissingHost()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Host\Exception\InvalidOptions
     * @expectedExceptionMessage Host is required and MUST be set in options.
     */
    public function testCanNotCreateWithoutHost(): void
    {
        Host::factory([]);
    }
}
