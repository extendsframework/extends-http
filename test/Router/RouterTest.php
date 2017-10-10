<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * Match.
     *
     * Test that router can match route and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Router::__construct()
     * @covers \ExtendsFramework\Http\Router\Router::addRoute()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $router = new Router();
        $matched = $router
            ->addRoute($route)
            ->route($request);

        $this->assertSame($match, $matched);
    }

    /**
     * Higher priority.
     *
     * Test that router can match route with higher priority and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Router::__construct()
     * @covers \ExtendsFramework\Http\Router\Router::addRoute()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testHigherPriority(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route1 = $this->createMock(RouteInterface::class);
        $route1
            ->expects($this->never())
            ->method('match');

        $route2 = $this->createMock(RouteInterface::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $router = new Router();
        $matched = $router
            ->addRoute($route1)
            ->addRoute($route2, 10)
            ->route($request);

        $this->assertSame($match, $matched);
    }

    /**
     * No match.
     *
     * Test that router can not match route and will return null.
     *
     * @covers \ExtendsFramework\Http\Router\Router::__construct()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testNoMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        /**
         * @var RequestInterface $request
         */
        $router = new Router();
        $match = $router->route($request);

        $this->assertNull($match);
    }
}
