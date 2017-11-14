<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\Http\Middleware\Router;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Exception\NotFound;
use ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Http\Router\Route\Query\Exception\InvalidQueryString;
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
            ->method('andAttribute')
            ->with('routeMatch', $match)
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
     * Not found.
     *
     * Test that when method is not allowed a 404 Not Found response will be returned.
     *
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::process()
     */
    public function testNotFound(): void
    {
        $chain = $this->createMock(MiddlewareChainInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willThrowException(new NotFound($request));

        /**
         * @var RouterInterface          $router
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * Method not allowed.
     *
     * Test that when method is not allowed a 405 Method Not Allowed response will be returned.
     *
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::process()
     */
    public function testMethodNotAllowed(): void
    {
        $chain = $this->createMock(MiddlewareChainInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willThrowException(new MethodNotAllowed('GET', ['PUT', 'POST']));

        /**
         * @var RouterInterface          $router
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(405, $response->getStatusCode());
        $this->assertSame([
            'Allow' => 'PUT, POST',
        ], $response->getHeaders());
    }

    /**
     * Invalid query string.
     *
     * Test that invalid query string exception will be caught and returned as a 400 response.
     *
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware::process()
     */
    public function testInvalidQueryString(): void
    {
        $chain = $this->createMock(MiddlewareChainInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willThrowException(new InvalidQueryString('limit', 'foo', '\d+'));

        /**
         * @var RouterInterface          $router
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame([
            'message' => 'Query string parameter "limit" value "foo" does not match constraint "\d+".',
        ], $response->getBody());
    }
}
