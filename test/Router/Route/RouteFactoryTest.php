<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Http\Router\Route\Group\GroupRoute;
use ExtendsFramework\Http\Router\Route\Method\MethodRoute;
use ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouteFactoryTest extends TestCase
{
    /**
     * Create.
     *
     * Test that factory can create an instance of GroupRoute and return it.
     *
     * @covers \ExtendsFramework\Http\Router\Route\RouteFactory::create()
     * @covers \ExtendsFramework\Http\Router\Route\RouteFactory::createGroupRoute()
     */
    public function testCreate(): void
    {
        $factory = new RouteFactory();
        $route = $factory->create([
            'type' => SchemeRoute::class,
            'options' => [
                'scheme' => 'https',
                'parameters' => [
                    'foo' => 'bar',
                ],
            ],
            'abstract' => true,
            'children' => [
                'foo' => [
                    'type' => MethodRoute::class,
                    'options' => [
                        'method' => 'GET',
                        'parameters' => [
                            'bar' => 'baz',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertInstanceOf(GroupRoute::class, $route);
    }

    /**
     * Invalid type.
     *
     * Test that factory can not create route for invalid type.
     *
     * @covers                   \ExtendsFramework\Http\Router\Route\RouteFactory::create()
     * @covers                   \ExtendsFramework\Http\Router\Route\RouteFactory::create()
     * @covers                   \ExtendsFramework\Http\Router\Route\Exception\InvalidRouteType::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Exception\InvalidRouteType
     * @expectedExceptionMessage Route must be instance or subclass of RouteInterface, got "string".
     */
    public function testInvalidType(): void
    {
        $factory = new RouteFactory();
        $factory->create([
            'type' => stdClass::class,
        ]);
    }
}
