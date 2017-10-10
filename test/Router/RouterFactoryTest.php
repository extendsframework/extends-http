<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\Method\MethodRoute;
use ExtendsFramework\Http\Router\Route\Path\PathRoute;
use ExtendsFramework\Http\Router\Route\Query\QueryRoute;
use ExtendsFramework\Http\Router\Route\RouteFactory;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute;
use PHPUnit\Framework\TestCase;

class RouterFactoryTest extends TestCase
{
    /**
     * Match.
     *
     * Test that RouterInterface can be created from routes, match request and return RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\RouterFactory::__construct()
     * @covers \ExtendsFramework\Http\Router\RouterFactory::create()
     */
    public function testMatch(): void
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

        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'limit' => '20',
                'offset' => '0',
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $request
            ->expects($this->exactly(3))
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $factory = new RouterFactory(new RouteFactory());
        $router = $factory->create([
            'scheme' => [
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
                        'abstract' => true,
                        'children' => [
                            'bar' => [
                                'type' => PathRoute::class,
                                'options' => [
                                    'path' => '/bar/:id',
                                    'constraints' => [
                                        'id' => '\d+',
                                    ],
                                    'parameters' => [
                                        'baz' => 'qux',
                                    ],
                                ],
                                'children' => [
                                    'query' => [
                                        'type' => QueryRoute::class,
                                        'options' => [
                                            'constraints' => [
                                                'limit' => '\d+',
                                                'offset' => '\d+',
                                            ],
                                            'parameters' => [
                                                'limit' => 20,
                                                'offset' => 0,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $match = $router->route($request);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame([
                'foo' => 'bar',
                'bar' => 'baz',
                'baz' => 'qux',
                'id' => '45',
                'limit' => '20',
                'offset' => '0',
            ], $match->getParameters());
            $this->assertSame(7, $match->getPathOffset());
        }
    }
}
