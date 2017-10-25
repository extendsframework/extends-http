<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Path;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class PathRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that path '/foo/33/bar/baz' will match '/:id/bar' and return an instance of RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getParameters()
     */
    public function testMatch(): void
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
        $path = new PathRoute('/:id/bar', [
            'id' => '\d+',
        ], [
            'foo' => 'bar',
        ]);
        $match = $path->match($request, 4);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(11, $match->getPathOffset());
            $this->assertSame([
                'foo' => 'bar',
                'id' => '33',
            ], $match->getParameters());
        }
    }

    /**
     * No match.
     *
     * Test that '/foo/bar/baz' will not match '/:id/bar' and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     */
    public function testNoMatch(): void
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
        $path = new PathRoute('/:id/bar', [
            'id' => '\d+',
        ]);
        $match = $path->match($request, 4);

        $this->assertNull($match);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = PathRoute::factory(PathRoute::class, $serviceLocator, [
            'path' => '/:id/bar',
            'constraints' => [
                'id' => '\d+',
            ],
            'parameters' => [
                'foo' => 'bar',
            ]
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
