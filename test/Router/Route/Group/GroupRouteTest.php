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
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::addChild()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     */
    public function testCanMatchRouteAndChildRoute(): void
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
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::isEndOfPath()
     */
    public function testCanMatchNonAbstractRoute(): void
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
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::isEndOfPath()
     */
    public function testCanNotMatchNonAbstractRouteWithLongerPath()
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
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::isEndOfPath()
     */
    public function testCanNotMatchAbstractRoute(): void
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
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     */
    public function testCanNotMatchRoute(): void
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
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\Exception\InvalidOptions::forMissingRoute()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Group\Exception\InvalidOptions
     * @expectedExceptionMessage Route is required and MUST be set in options.
     */
    public function testCanNotCreateWithoutRoute(): void
    {
        GroupRoute::factory([]);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Group\Exception\InvalidOptions::forMissingChildren()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Group\Exception\InvalidOptions
     * @expectedExceptionMessage Children are required and MUST be set in options.
     */
    public function testCanNotCreateWithoutChildren(): void
    {
        $route = $this->createMock(RouteInterface::class);

        GroupRoute::factory([
            'route' => $route,
        ]);
    }
}
