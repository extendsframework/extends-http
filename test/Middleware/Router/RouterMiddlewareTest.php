<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Router;

use ExtendsFramework\Http\Controller\ControllerInterface;
use ExtendsFramework\Http\Controller\Exception\ActionNotFound;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\ServiceLocator\Exception\ServiceNotFound;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RouterMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that route can be matched, controller will be dispatched and a response will be returned.
     *
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::getController()
     */
    public function testMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'controller' => 'foo',
            ]);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willReturn($match);

        $controller = $this->createMock(ControllerInterface::class);
        $controller
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willReturn($response);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with('foo')
            ->willReturn($controller);

        /**
         * @var RouterInterface          $router
         * @var ServiceLocatorInterface  $serviceLocator
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router, $serviceLocator);
        $processed = $middleware->process($request, $chain);

        $this->assertSame($response, $processed);
    }

    /**
     * No match.
     *
     * Test that when no route can be matched the chain will be called and returned.
     *
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
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

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var RouterInterface          $router
         * @var ServiceLocatorInterface  $serviceLocator
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router, $serviceLocator);
        $processed = $middleware->process($request, $chain);

        $this->assertSame($response, $processed);
    }

    /**
     * Controller parameter missing.
     *
     * Test that the controller parameter is missing in the match parameters and a exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
     * @covers                   \ExtendsFramework\Http\Middleware\Router\Exception\ControllerParameterMissing::__construct()
     * @expectedException        \ExtendsFramework\Http\Middleware\Router\Exception\ControllerParameterMissing
     * @expectedExceptionMessage Controller key is not set in route match parameters.
     */
    public function testControllerParameterMissing(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willReturn($match);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var RouterInterface          $router
         * @var ServiceLocatorInterface  $serviceLocator
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router, $serviceLocator);
        $middleware->process($request, $chain);
    }

    /**
     * Controller not found.
     *
     * Test that when route match parameter 'controller' is not set the exception ControllerNotFound is thrown.
     *
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
     * @covers                   \ExtendsFramework\Http\Middleware\Router\Exception\ControllerNotFound::__construct()
     * @expectedException        \ExtendsFramework\Http\Middleware\Router\Exception\ControllerNotFound
     * @expectedExceptionMessage Controller for key "foo" can not be retrieved from service locator. See previous
     *                           exception for more details.
     */
    public function testControllerNotFound(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'controller' => 'foo',
            ]);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willReturn($match);

        $exception = $this->createMock(ServiceNotFound::class);

        /**
         * @var ServiceNotFound $exception
         */
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with('foo')
            ->willThrowException($exception);

        /**
         * @var RouterInterface          $router
         * @var ServiceLocatorInterface  $serviceLocator
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router, $serviceLocator);
        $middleware->process($request, $chain);
    }

    /**
     * Controller dispatch failed.
     *
     * Test that a ControllerException can be caught and the exception ControllerDispatchFailed will be thrown.
     *
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
     * @covers                   \ExtendsFramework\Http\Middleware\Router\Exception\ControllerDispatchFailed::__construct()
     * @expectedException        \ExtendsFramework\Http\Middleware\Router\Exception\ControllerDispatchFailed
     * @expectedExceptionMessage Failed to dispatch request to controller. See previous exception for more details.
     */
    public function testControllerDispatchFailed(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'controller' => 'foo',
            ]);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willReturn($match);

        $exception = $this->createMock(ActionNotFound::class);

        /**
         * @var ActionNotFound $exception
         */
        $controller = $this->createMock(ControllerInterface::class);
        $controller
            ->expects($this->once())
            ->method('dispatch')
            ->with($request)
            ->willThrowException($exception);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with('foo')
            ->willReturn($controller);

        /**
         * @var RouterInterface          $router
         * @var ServiceLocatorInterface  $serviceLocator
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RouterMiddleware($router, $serviceLocator);
        $middleware->process($request, $chain);
    }
}
