<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Scheme;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class SchemeRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that route will match scheme HTTPS and return instance of RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute::match()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('https');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $scheme = SchemeRoute::factory([
            'scheme' => 'https',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);
        $match = $scheme->match($request, 5);

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
     * Test that route will not match scheme HTTP and will return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute::match()
     */
    public function testNoMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('http');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $scheme = SchemeRoute::factory([
            'scheme' => 'https',
        ]);
        $match = $scheme->match($request, 5);

        $this->assertNull($match);
    }

    /**
     * Missing scheme.
     *
     * Test that factory will throw an exception for missing scheme in options.
     *
     * @covers                   \ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Scheme\Exception\MissingScheme::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Scheme\Exception\MissingScheme
     * @expectedExceptionMessage Scheme is required and must be set in options.
     */
    public function testMissingScheme(): void
    {
        SchemeRoute::factory([]);
    }
}
