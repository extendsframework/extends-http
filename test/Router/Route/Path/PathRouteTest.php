<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Path;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class PathRouteTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getParameters()
     */
    public function testCanMatchPath(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo/33/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $path = PathRoute::factory([
            'path' => '/:id/bar',
            'constraints' => [
                'id' => '\d+',
            ],
            'parameters' => [
                'foo' => 'bar',
            ]
        ]);
        $match = $path->match($request, 4);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        $this->assertSame(11, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
            'id' => '33',
        ], $match->getParameters()->extract());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     */
    public function testCanNotMatchPath(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $path = PathRoute::factory([
            'path' => '/:id/bar',
            'constraints' => [
                'id' => '\d+',
            ]
        ]);
        $match = $path->match($request, 4);

        $this->assertNull($match);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Path\PathRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Path\Exception\InvalidOptions::forMissingPath()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Path\Exception\InvalidOptions
     * @expectedExceptionMessage Path is required and MUST be set in options.
     */
    public function testCanNotCreateWithoutPath(): void
    {
        PathRoute::factory([]);
    }
}
