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
     * Test that path '/foo/:first_name/bar' will match '/:first_name/bar' and return an instance of
     * RouteMatchInterface.
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
            ->willReturn('/foo/John/bar');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $path = new PathRoute('/:first_name/bar', [
            'first_name' => '\w+',
        ], [
            'foo' => 'bar',
        ]);
        $match = $path->match($request, 4);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(13, $match->getPathOffset());
            $this->assertSame([
                'foo' => 'bar',
                'first_name' => 'John',
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
     * Strict mode.
     *
     * Test that path must be a exact match.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::setStrict()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     */
    public function testStrictMode(): void
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
        $path = new PathRoute('/foo/bar');
        $match = $path->match($request, 0);

        $this->assertNull($match);
    }

    /**
     * Non strict mode.
     *
     * Test that path can have offset left and still matches.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::setStrict()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     */
    public function testNonStrictMode(): void
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
        $path = new PathRoute('/foo/bar');
        $match = $path
            ->setStrict(false)
            ->match($request, 0);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
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
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
