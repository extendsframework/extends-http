<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class MethodRouteTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::match()
     */
    public function testCanMatchSegment(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        /**
         * @var RequestInterface $request
         */
        $method = MethodRoute::factory([
            'method' => 'POST',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);
        $match = $method->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        $this->assertSame(0, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
        ], $match->getParameters()->extract());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::match()
     */
    public function testCanNotMatchSegment(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        /**
         * @var RequestInterface $request
         */
        $method = MethodRoute::factory([
            'method' => 'POST',
        ]);
        $match = $method->match($request, 5);

        $this->assertNull($match);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\Exception\InvalidOptions::forMissingMethod()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Method\Exception\InvalidOptions
     * @expectedExceptionMessage Method is required and MUST be set in options.
     */
    public function testCanNotCreateWithoutMethod(): void
    {
        MethodRoute::factory([]);
    }
}
