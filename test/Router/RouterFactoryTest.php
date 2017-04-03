<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\Method\Method;
use ExtendsFramework\Http\Router\Route\Path\Path;
use ExtendsFramework\Http\Router\Route\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class RouterFactoryTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\RouterFactory::create()
     * @covers \ExtendsFramework\Http\Router\RouterFactory::createRoute
     */
    public function testCanCreateRouterFromRoutes(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/bar/45');

        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('https');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $request
            ->expects($this->exactly(2))
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $factory = new RouterFactory();
        $router = $factory->create([
            'scheme' => [
                'type' => Scheme::class,
                'options' => [
                    'scheme' => 'https',
                    'parameters' => [
                        'foo' => 'bar',
                    ],
                ],
                'abstract' => true,
                'children' => [
                    'foo' => [
                        'type' => Method::class,
                        'options' => [
                            'method' => 'GET',
                            'parameters' => [
                                'bar' => 'baz',
                            ],
                        ],
                        'abstract' => true,
                        'children' => [
                            'bar' => [
                                'type' => Path::class,
                                'options' => [
                                    'path' => '/bar/:id',
                                    'constraints' => [
                                        'id' => '\d+',
                                    ],
                                    'parameters' => [
                                        'baz' => 'qux',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $match = $router->route($request);

        $this->assertSame([
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'qux',
            'id' => '45',
        ], $match->getParameters()->extract());
        $this->assertSame(7, $match->getPathOffset());
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\RouterFactory::create()
     * @covers                   \ExtendsFramework\Http\Router\RouterFactory::createRoute
     * @covers                   \ExtendsFramework\Http\Router\Exception\InvalidRouterConfig::forRouteType()
     * @expectedException        \ExtendsFramework\Http\Router\Exception\InvalidRouterConfig
     * @expectedExceptionMessage Route must be instance or subclass of RouteInterface, got "array".
     */
    public function testCanNotCreateRouterFromInvalidRouteType(): void
    {
        $factory = new RouterFactory();
        $factory->create([
            'scheme' => [
                'type' => [],
            ],
        ]);
    }
}
