<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Router\Route\Method\Method::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Method\Method::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Method\Method::match()
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
        $method = Method::factory([
            'method' => 'POST',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);
        $match = $method->match($request, 5);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
        ], $match->getParameters()->extract());
    }

    /**
     * @covers \ExtendsFramework\Http\Router\Route\Method\Method::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Method\Method::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Method\Method::match()
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
        $method = Method::factory([
            'method' => 'POST',
        ]);
        $match = $method->match($request, 5);

        $this->assertNull($match);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\Method::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\Exception\InvalidOptions::forMissingMethod()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Method\Exception\InvalidOptions
     * @expectedExceptionMessage Method is required and MUST be set in options.
     */
    public function testCanNotCreateWithoutMethod(): void
    {
        Method::factory([]);
    }
}
