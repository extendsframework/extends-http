<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Path;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::getPattern()
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::getParameters()
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
        $path = Path::factory([
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
        $this->assertSame(15, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
            'id' => '33',
        ], $match->getParameters()->extract());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\Path::getPattern()
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
        $path = Path::factory([
            'path' => '/:id/bar',
            'constraints' => [
                'id' => '\d+',
            ]
        ]);
        $match = $path->match($request, 4);

        $this->assertNull($match);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Path\Path::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Path\Exception\InvalidOptions::forMissingPath()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Path\Exception\InvalidOptions
     * @expectedExceptionMessage Path is required and MUST be set in options.
     */
    public function testCanNotCreateWithoutPath(): void
    {
        Path::factory([]);
    }
}
