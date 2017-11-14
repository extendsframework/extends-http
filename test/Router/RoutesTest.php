<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Group\GroupRoute;
use ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Http\Router\Route\Method\MethodRoute;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    /**
     * Match.
     *
     * Test that route will be matched and returned.
     *
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
     */
    public function testMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route1 = $this->createMock(RouteInterface::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn(null);

        $route2 = $this->createMock(RouteInterface::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RequestInterface $request
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         */
        $routes = new RoutesStub();
        $matched = $routes
            ->addRoute($route1)
            ->addRoute($route2)
            ->match($request);

        $this->assertSame($match, $matched);
    }

    /**
     * Not match.
     *
     * Test that no route will be matched and null will be returned.
     *
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
     */
    public function testNoMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        /**
         * @var RequestInterface $request
         */
        $routes = new RoutesStub();

        $this->assertNull($routes->match($request));
    }

    /**
     * Method not allowed.
     *
     * Test that none of the method routes is allowed and exception will be thrown with allowed methods.
     *
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
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
        $routes = new RoutesStub();
        $routes
            ->addRoute($route1)
            ->addRoute($route2);

        try {
            $routes->match($request);
        } catch (MethodNotAllowed $exception) {
            $this->assertSame(['POST', 'PUT', 'DELETE'], $exception->getAllowedMethods());
        }
    }

    /**
     * Method allowed.
     *
     * Test that second method route is allowed and first exception not will be thrown.
     *
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
     */
    public function testMethodAllowed(): void
    {
        $match = $this->createMock(RouteMatchInterface::class);

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
            ->willReturn($match);

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $routes = new RoutesStub();
        $matched = $routes
            ->addRoute($route1)
            ->addRoute($route2)
            ->match($request);

        $this->assertSame($match, $matched);
    }

    /**
     * Route order.
     *
     * Test that group route will be matched first.
     *
     * @covers \ExtendsFramework\Http\Router\Routes::addRoute()
     * @covers \ExtendsFramework\Http\Router\Routes::matchRoutes()
     * @covers \ExtendsFramework\Http\Router\Routes::getRoutes()
     */
    public function testRouteOrder(): void
    {
        $match = $this->createMock(RouteMatchInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $route1 = $this->createMock(MethodRoute::class);
        $route1
            ->expects($this->never())
            ->method('match');

        $route2 = $this->createMock(GroupRoute::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn(null);

        $route3 = $this->createMock(GroupRoute::class);
        $route3
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RouteInterface   $route3
         * @var RequestInterface $request
         */
        $routes = new RoutesStub();
        $matched = $routes
            ->addRoute($route1)
            ->addRoute($route2)
            ->addRoute($route3)
            ->match($request);

        $this->assertSame($match, $matched);
    }
}

class RoutesStub
{
    use Routes;

    /**
     * @param RequestInterface $request
     * @return Route\RouteMatchInterface|null
     * @throws Route\RouteException
     */
    public function match(RequestInterface $request): ?RouteMatchInterface
    {
        return $this->matchRoutes($request, 0);
    }
}
