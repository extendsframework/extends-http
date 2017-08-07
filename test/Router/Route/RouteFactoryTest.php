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
     * @covers \ExtendsFramework\Http\Router\Route\RouteFactory::create()
     * @covers \ExtendsFramework\Http\Router\Route\RouteFactory::createGroupRoute()
     */
    public function testCanCreateRouteFromConfig(): void
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
     * @covers                   \ExtendsFramework\Http\Router\Route\RouteFactory::create()
     * @covers                   \ExtendsFramework\Http\Router\Route\RouteFactory::create()
     * @covers                   \ExtendsFramework\Http\Router\Route\RouteException::forInvalidRouteType()
     * @expectedException        \ExtendsFramework\Http\Router\Route\RouteException
     * @expectedExceptionMessage Route MUST be instance or subclass of RouteInterface, got "string".
     */
    public function testCanNotCreateRouteFromConfigForInvalidType(): void
    {
        $factory = new RouteFactory();
        $factory->create([
            'type' => stdClass::class,
        ]);
    }
}
