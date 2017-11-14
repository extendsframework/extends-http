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
     * @covers \ExtendsFramework\Http\Router\Router::addRoute()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
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
     * No match.
     *
     * Test that router can not match route and will return null.
     *
     * @covers                   \ExtendsFramework\Http\Router\Router::route()
     * @covers                   \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers                   \ExtendsFramework\Http\Router\Routes::getRoutes()
     * @covers                   \ExtendsFramework\Http\Router\Exception\NotFound::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Exception\NotFound
     * @expectedExceptionMessage
     */
    public function testNoMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        /**
         * @var RequestInterface $request
         */
        $router = new Router();
        $router->route($request);
    }
}
