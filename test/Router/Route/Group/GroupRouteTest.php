<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class GroupRouteTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     */
    public function testCanMatchNonAbstractRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match1 = $this->createMock(RouteMatchInterface::class);
        $match1
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(5);

        $route1 = $this->createMock(RouteInterface::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match1);

        $route2 = $this->createMock(RouteInterface::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 5)
            ->willReturn(null);

        /**
         * @var RequestInterface $request
         */
        $group = GroupRoute::factory([
            'route' => $route1,
            'children' => [
                $route2
            ],
            'abstract' => false,
        ]);
        $match = $group->match($request, 0);

        $this->assertSame($match1, $match);
    }

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
        $match = $group->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Group\GroupRoute::match()
     */
    public function testCanNotMatchAbstractRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match1 = $this->createMock(RouteMatchInterface::class);
        $match1
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(5);

        $route1 = $this->createMock(RouteInterface::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match1);

        $route2 = $this->createMock(RouteInterface::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 5)
            ->willReturn(null);

        /**
         * @var RequestInterface $request
         */
        $group = GroupRoute::factory([
            'route' => $route1,
            'children' => [
                $route2
            ],
            'abstract' => true,
        ]);
        $match = $group->match($request, 0);

        $this->assertNull($match);
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
        $match = $group->match($request, 0);

        $this->assertNull($match);
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
