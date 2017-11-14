<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Http\Router\Route\Method\MethodRoute;
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

    /**
     * Method not allowed.
     *
     * Test that method is not allowed by multiple child routes and exception will thrown with allowed methods.
     *
     * @covers \ExtendsFramework\Http\Router\Router::addRoute()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testMethodNotAllowed(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $route1 = $this->createMock(MethodRoute::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willThrowException(new MethodNotAllowed('GET', ['POST', 'PUT']));

        $route2 = $this->createMock(MethodRoute::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willThrowException(new MethodNotAllowed('GET', ['DELETE']));

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $router = new Router();
        $router
            ->addRoute($route1)
            ->addRoute($route2);

        try {
            $router->route($request);
        } catch (MethodNotAllowed $exception) {
            $this->assertSame(['POST', 'PUT', 'DELETE'], $exception->getAllowedMethods());
        }
    }

    /**
     * Method not allowed.
     *
     * Test that method is allowed after failed child route and match will be returned.
     *
     * @covers \ExtendsFramework\Http\Router\Router::addRoute()
     * @covers \ExtendsFramework\Http\Router\Router::route()
     */
    public function testMethodAllowed(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route1 = $this->createMock(MethodRoute::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willThrowException(new MethodNotAllowed('GET', ['POST', 'PUT']));

        $route2 = $this->createMock(MethodRoute::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $router = new Router();
        $matched = $router
            ->addRoute($route1)
            ->addRoute($route2)
            ->route($request);

        $this->assertSame($match, $matched);
    }

}
