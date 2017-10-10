<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class GroupRouteTest extends TestCase
{
    /**
     * Child route.
     *
     * Test that group route will match child route for request and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::addChild()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     */
    public function testChildRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match2 = $this->createMock(RouteMatchInterface::class);

        $route2 = $this->createMock(RouteInterface::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 5)
            ->willReturn($match2);

        $match1 = $this->createMock(RouteMatchInterface::class);
        $match1
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(5);

        $match1
            ->expects($this->once())
            ->method('merge')
            ->with($match2)
            ->willReturn(
                $this->createMock(RouteMatchInterface::class)
            );

        $route1 = $this->createMock(RouteInterface::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 5)
            ->willReturn($match1);

        /**
         * @var RequestInterface $request
         */
        $group = GroupRoute::factory([
            'route' => $route1,
            'children' => [
                $route2
            ],
        ]);
        $matched = $group->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $matched);
    }

    /**
     * Non abstract route.
     *
     * Test that group route will match non abstract route for request and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::isEndOfPath()
     */
    public function testNonAbstractRoute(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/quux');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(5);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RequestInterface $request
         */
        $group = GroupRoute::factory([
            'route' => $route,
            'children' => [],
            'abstract' => false,
        ]);
        $matched = $group->match($request, 0);

        $this->assertSame($match, $matched);
    }

    /**
     * End of path.
     *
     * Test that group route can not match end of path and will return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::isEndOfPath()
     */
    public function testEndOfPath()
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/quux/foo');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(5);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RequestInterface $request
         */
        $group = GroupRoute::factory([
            'route' => $route,
            'children' => [],
            'abstract' => false,
        ]);
        $matched = $group->match($request, 0);

        $this->assertNull($matched);
    }

    /**
     * Abstract route.
     *
     * Test that group route will not match abstract self and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::isEndOfPath()
     */
    public function testAbstractRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RequestInterface $request
         */
        $group = GroupRoute::factory([
            'route' => $route,
            'children' => []
        ]);
        $matched = $group->match($request, 0);

        $this->assertNull($matched);
    }

    /**
     * No route match.
     *
     * Test that inner route will not match and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     */
    public function testNoRouteMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn(null);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = GroupRoute::factory([
            'route' => $route,
            'children' => [],
        ]);
        $matched = $group->match($request, 0);

        $this->assertNull($matched);
    }

    /**
     * Missing route.
     *
     * Test that factory will throw an exception for missing route in options.
     *
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\Exception\MissingRoute::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Group\Exception\MissingRoute
     * @expectedExceptionMessage Route is required and must be set in options.
     */
    public function testMissingRoute(): void
    {
        GroupRoute::factory([]);
    }

    /**
     * Missing children.
     *
     * Test that factory will throw an exception for missing children in options.
     *
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\Exception\MissingChildren::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Group\Exception\MissingChildren
     * @expectedExceptionMessage Children are required and must be set in options.
     */
    public function testMissingChildren(): void
    {
        $route = $this->createMock(RouteInterface::class);

        GroupRoute::factory([
            'route' => $route,
        ]);
    }
}
