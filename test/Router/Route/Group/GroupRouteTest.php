<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\Path\PathRoute;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class GroupRouteTest extends TestCase
{
    /**
     * Child route.
     *
     * Test that group route will match child route for request and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
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
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route1);
        $matched = $group
            ->addRoute($route2)
            ->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $matched);
    }

    /**
     * Non abstract route.
     *
     * Test that group route will match non abstract route for request and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
     */
    public function testNonAbstractRoute(): void
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
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route, false);
        $matched = $group->match($request, 0);

        $this->assertSame($match, $matched);
    }

    /**
     * Abstract route.
     *
     * Test that group route will not match abstract self and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
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
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route);
        $matched = $group->match($request, 0);

        $this->assertNull($matched);
    }

    /**
     * No route match.
     *
     * Test that inner route will not match and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
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
        $group = new GroupRoute($route);
        $matched = $group->match($request, 0);

        $this->assertNull($matched);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = GroupRoute::factory(GroupRoute::class, $serviceLocator, [
            'route' => $this->createMock(RouteInterface::class),
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
