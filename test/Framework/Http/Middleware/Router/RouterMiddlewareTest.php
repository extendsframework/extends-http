<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\Http\Middleware\Router;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\RouterInterface;
use PHPUnit\Framework\TestCase;

class RouterMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that route can be matched, controller will be dispatched and a response will be returned.
     *
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::process()
     */
    public function testMatch(): void
    {
        $match = $this->createMock(RouteMatchInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('withRouteMatch')
            ->with($match)
            ->willReturnSelf();

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willReturn($match);

        /**
         * @var RouterInterface          $router
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * No match.
     *
     * Test that when no route can be matched the chain will be called and returned.
     *
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::process()
     */
    public function testNoMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willReturn(null);

        /**
         * @var RouterInterface          $router
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router);
        $processed = $middleware->process($request, $chain);

        $this->assertSame($response, $processed);
    }
}
