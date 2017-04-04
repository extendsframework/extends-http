<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class RouteMatchTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\RouteMatch::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\RouteMatch::getParameters()
     * @covers \ExtendsFramework\Http\Router\Route\RouteMatch::getPathOffset()
     */
    public function testCanCreateRouteMatch(): void
    {
        $parameters = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $parameters
         */
        $match = new RouteMatch($parameters, 15);

        $this->assertSame($parameters, $match->getParameters());
        $this->assertSame(15, $match->getPathOffset());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\RouteMatch::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\RouteMatch::merge()
     * @covers \ExtendsFramework\Http\Router\Route\RouteMatch::getParameters()
     * @covers \ExtendsFramework\Http\Router\Route\RouteMatch::getPathOffset()
     */
    public function testCanMergeWithOtherRouteMatch(): void
    {
        $parameters2 = $this->createMock(ContainerInterface::class);

        $parameters3 = $this->createMock(ContainerInterface::class);

        $parameters1 = $this->createMock(ContainerInterface::class);
        $parameters1
            ->expects($this->once())
            ->method('merge')
            ->with($parameters2)
            ->willReturn($parameters3);

        /**
         * @var ContainerInterface $parameters1
         */
        $match1 = new RouteMatch($parameters1, 10);

        /**
         * @var ContainerInterface $parameters2
         */
        $match2 = new RouteMatch($parameters2, 15);

        $match3 = $match1->merge($match2);

        $this->assertSame($parameters3, $match3->getParameters());
        $this->assertSame(15, $match3->getPathOffset());
    }
}
