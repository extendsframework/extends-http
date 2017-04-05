<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Scheme;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class SchemeTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\Scheme::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\Scheme::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\Scheme::match()
     */
    public function testCanMatchSegment(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('https');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $scheme = Scheme::factory([
            'scheme' => 'https',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);
        $match = $scheme->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        $this->assertSame(0, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
        ], $match->getParameters()->extract());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\Scheme::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\Scheme::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\Scheme::match()
     */
    public function testCanNotMatchSegment(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('http');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $scheme = Scheme::factory([
            'scheme' => 'https',
        ]);
        $match = $scheme->match($request, 5);

        $this->assertNull($match);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Scheme\Scheme::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Scheme\Exception\InvalidOptions::forMissingScheme()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Scheme\Exception\InvalidOptions
     * @expectedExceptionMessage Scheme is required and MUST be set in options.
     */
    public function testCanNotCreateWithoutScheme(): void
    {
        Scheme::factory([]);
    }
}
