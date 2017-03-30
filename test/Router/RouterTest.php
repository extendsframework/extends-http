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
     * @covers \ExtendsFramework\Http\Router\Router::__construct()
     * @covers \ExtendsFramework\Http\Router\Router::addRoute()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testCanMatchRequest(): void
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
     * @covers \ExtendsFramework\Http\Router\Router::__construct()
     * @covers \ExtendsFramework\Http\Router\Router::addRoute()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testWillMatchRouteWithHigherPriority(): void
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
     * @covers \ExtendsFramework\Http\Router\Router::__construct()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testCanNotMatchRequest(): void
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
