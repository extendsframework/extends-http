<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Router;

use Exception;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Controller\ControllerInterface;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RouterMiddlewareTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::getController()
     */
    public function testWillReturnResponseOnRouteMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        $parameters = $this->createMock(ContainerInterface::class);
        $parameters
            ->expects($this->once())
            ->method('get')
            ->with('controller')
            ->willReturn('foo');

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn($parameters);

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
            ->method('get')
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
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
     */
    public function testWillProceedChainWhenRouteIsNotMatched(): void
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
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::__construct()
     * @covers                   \ExtendsFramework\Http\Middleware\Router\RouterMiddleware::process()
     * @covers                   \ExtendsFramework\Http\Middleware\Exception\ExecutionFailed::fromThrowable()
     * @expectedException        \ExtendsFramework\Http\Middleware\Exception\ExecutionFailed
     * @expectedExceptionMessage Foo is not bar.
     */
    public function testCanCatchAndWrapException(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willThrowException(new Exception('Foo is not bar.'));

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
}
