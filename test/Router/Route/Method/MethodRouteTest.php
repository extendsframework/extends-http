<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
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
        $method = new MethodRoute('POST', [
            'foo' => 'bar',
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
     * Method not allowed.
     *
     * Test that method GET is not allowed and an exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\MethodRoute::__construct()
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\MethodRoute::match()
     * @covers                   \ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed::__construct()
     * @expectedException        \ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed
     * @expectedExceptionMessage Method "GET" is not allowed.
     */
    public function testMethodNotAllowed(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        /**
         * @var RequestInterface $request
         */
        $method = new MethodRoute('POST');
        $method->match($request, 5);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Method\MethodRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = MethodRoute::factory(MethodRoute::class, $serviceLocator, [
            'method' => 'POST',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }

    /**
     * Methods.
     *
     * Test that constants contain correct methods.
     */
    public function testMethods(): void
    {
        $this->assertSame('OPTIONS', MethodRoute::METHOD_OPTIONS);
        $this->assertSame('GET', MethodRoute::METHOD_GET);
        $this->assertSame('HEAD', MethodRoute::METHOD_HEAD);
        $this->assertSame('POST', MethodRoute::METHOD_POST);
        $this->assertSame('PUT', MethodRoute::METHOD_PUT);
        $this->assertSame('DELETE', MethodRoute::METHOD_DELETE);
        $this->assertSame('TRACE', MethodRoute::METHOD_TRACE);
        $this->assertSame('CONNECT', MethodRoute::METHOD_CONNECT);
    }
}
