<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class AbstractControllerTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Controller\AbstractController::dispatch()
     * @covers \ExtendsFramework\Http\Controller\AbstractController::getMethod()
     * @covers \ExtendsFramework\Http\Controller\AbstractController::getAction()
     */
    public function testCanGetMethodForAction(): void
    {
        $attributes = $this->createMock(ContainerInterface::class);
        $attributes
            ->expects($this->once())
            ->method('get')
            ->with('action')
            ->willReturn('foo.not-found');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn($attributes);

        /**
         * @var RequestInterface $request
         */
        $controller = new FooController();
        $response = $controller->dispatch($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::dispatch()
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::getMethod()
     * @covers                   \ExtendsFramework\Http\Controller\AbstractController::getAction()
     * @covers                   \ExtendsFramework\Http\Controller\Exception\MethodNotFound::forAction()
     * @expectedException        \ExtendsFramework\Http\Controller\Exception\MethodNotFound
     * @expectedExceptionMessage No controller action method can be found for action "bar".
     */
    public function testCanNotGetMethodForAction(): void
    {
        $attributes = $this->createMock(ContainerInterface::class);
        $attributes
            ->expects($this->once())
            ->method('get')
            ->with('action')
            ->willReturn('bar');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn($attributes);

        /**
         * @var RequestInterface $request
         */
        $controller = new FooController();
        $controller->dispatch($request);
    }
}

class FooController extends AbstractController
{
    /**
     * @return ResponseInterface
     */
    protected function fooNotFoundAction(): ResponseInterface
    {
        return new Response();
    }
}
