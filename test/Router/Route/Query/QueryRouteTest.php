<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class QueryRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that route will match '?limit=20&offset=0' and return an instance of RouteMatchInterface
     *
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::getPattern()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::getParameters()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'offset' => '0',
                'limit' => '20',
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $path = new QueryRoute([
            'limit' => '\d+',
            'offset' => '\d+',
        ], [
            'offset' => '0',
        ]);
        $match = $path->match($request, 4);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(0, $match->getPathOffset());
            $this->assertSame([
                'offset' => '0',
                'limit' => '20',
            ], $match->getParameters());

        }
    }

    /**
     * No match.
     *
     * Test that route will not match empty query and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::getPattern()
     */
    public function testNoMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'limit' => 'foo',
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $path = new QueryRoute([
            'limit' => '\d+',
        ]);
        $match = $path->match($request, 4);

        $this->assertNull($match);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = QueryRoute::factory(QueryRoute::class, $serviceLocator, [
            'path' => '/:id/bar',
            'constraints' => [
                'limit' => '\d+',
                'offset' => '\d+',
            ],
            'parameters' => [
                'offset' => '0',
            ]
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
