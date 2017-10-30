<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class AbstractControllerTest extends TestCase
{
    /**
     * Dispatch.
     *
     * Test that $request can be dispatched to $controller and $response will be returned.
     *
     * @covers \ExtendsFramework\Http\Controller\AbstractController::dispatch()
     * @covers \ExtendsFramework\Http\Controller\AbstractController::getMethod()
     * @covers \ExtendsFramework\Http\Controller\AbstractController::getAction()
     * @covers \ExtendsFramework\Http\Controller\AbstractController::normalizeAction()
     */
    public function testDispatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'action' => 'foo.not-found'
            ]);

        /**
         * @var RequestInterface    $request
         * @var RouteMatchInterface $match
         */
        $controller = new ControllerStub();
        $response = $controller->dispatch($request, $match);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * Action not found.
     *
     * Test that action attribute can not be found in $request and an exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::dispatch()
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::getMethod()
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::getAction()
     * @covers                   \ExtendsFramework\Http\Controller\Exception\ActionNotFound::__construct()
     * @expectedException        \ExtendsFramework\Http\Controller\Exception\ActionNotFound
     * @expectedExceptionMessage No controller action was found in request.
     */
    public function testActionNotFound(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        /**
         * @var RequestInterface    $request
         * @var RouteMatchInterface $match
         */
        $controller = new ControllerStub();
        $controller->dispatch($request, $match);
    }

    /**
     * Method not found.
     *
     * Test that controller method for action can not be found and an exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::dispatch()
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::getMethod()
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::getAction()
     * @covers                   \ExtendsFramework\Http\Controller\Exception\MethodNotFound::__construct()
     * @expectedException        \ExtendsFramework\Http\Controller\Exception\MethodNotFound
     * @expectedExceptionMessage No controller method can be found for action "bar".
     */
    public function testMethodNotFound(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'action' => 'bar'
            ]);

        /**
         * @var RequestInterface    $request
         * @var RouteMatchInterface $match
         */
        $controller = new ControllerStub();
        $controller->dispatch($request, $match);
    }
}

class ControllerStub extends AbstractController
{
    /**
     * @return ResponseInterface
     */
    protected function fooNotFoundAction(): ResponseInterface
    {
        return new Response();
    }
}
