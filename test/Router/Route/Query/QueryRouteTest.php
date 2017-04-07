<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query;

use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class QueryRouteTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::getPattern()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::getParameters()
     */
    public function testCanMatchQuery(): void
    {
        $query = $this->createMock(ContainerInterface::class);
        $query
            ->expects($this->exactly(2))
            ->method('has')
            ->withConsecutive(['limit'], ['offset'])
            ->willReturnOnConsecutiveCalls(true, false);

        $query
            ->expects($this->once())
            ->method('get')
            ->with('limit')
            ->willReturn('20');

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $path = QueryRoute::factory([
            'constraints' => [
                'limit' => '\d+',
                'offset' => '\d+',
            ],
            'parameters' => [
                'offset' => '0',
            ]
        ]);
        $match = $path->match($request, 4);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        $this->assertSame(0, $match->getPathOffset());
        $this->assertSame([
            'offset' => '0',
            'limit' => '20',
        ], $match->getParameters()->extract());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::getPattern()
     */
    public function testCanNotMatchQuery(): void
    {
        $query = $this->createMock(ContainerInterface::class);
        $query
            ->expects($this->once())
            ->method('has')
            ->with('limit')
            ->willReturn(true);

        $query
            ->expects($this->once())
            ->method('get')
            ->with('limit')
            ->willReturn('foo');

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $path = QueryRoute::factory([
            'path' => '/:id/bar',
            'constraints' => [
                'limit' => '\d+',
            ]
        ]);
        $match = $path->match($request, 4);

        $this->assertNull($match);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Query\QueryRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Query\Exception\InvalidOptions::forMissingConstraints()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Query\Exception\InvalidOptions
     * @expectedExceptionMessage Constraints are required and MUST be set in options.
     */
    public function testCanNotCreateWithoutQuery(): void
    {
        QueryRoute::factory([]);
    }
}