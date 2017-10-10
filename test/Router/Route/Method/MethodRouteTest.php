<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class MethodRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that POST method will be matched and a instance of RouteMatchInterface will be returned.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::match()
     */
    public function testMatch(): void
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
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(0, $match->getPathOffset());
            $this->assertSame([
                'foo' => 'bar',
            ], $match->getParameters());
        }
    }

    /**
     * No match.
     *
     * Test that method GET can not be matched and null will be returned.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::match()
     */
    public function testNoMatch(): void
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
     * Missing method.
     *
     * Test that factory will throw an exception for missing method in options.
     *
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\Exception\MissingMethod::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Method\Exception\MissingMethod
     * @expectedExceptionMessage Method is required and must be set in options.
     */
    public function testCanNotCreateWithoutMethod(): void
    {
        MethodRoute::factory([]);
    }
}
