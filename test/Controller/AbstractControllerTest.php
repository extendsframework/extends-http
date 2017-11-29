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
     * @covers \ExtendsFramework\Http\Controller\AbstractController::getParameters()
     * @covers \ExtendsFramework\Http\Controller\AbstractController::getRequest()
     * @covers \ExtendsFramework\Http\Controller\AbstractController::getRouteMatch()
     */
    public function testDispatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->exactly(1))
            ->method('getParameters')
            ->willReturn([
                'action' => 'foo.fancy-action',
                'someId' => 33,
                'slug' => 'foo-bar-baz',
            ]);

        $match
            ->expects($this->exactly(2))
            ->method('getParameter')
            ->withConsecutive(
                ['someId'],
                ['slug']
            )
            ->willReturnOnConsecutiveCalls(
                33,
                'foo-bar-baz'
            );

        /**
         * @var RequestInterface    $request
         * @var RouteMatchInterface $match
         */
        $controller = new ControllerStub();
        $response = $controller->dispatch($request, $match);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        if ($response instanceof ResponseInterface) {
            $this->assertSame([
                'request' => $request,
                'routeMatch' => $match,
                'someId' => 33,
                'slug' => 'foo-bar-baz',
            ], $response->getBody());
        }
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
}

class ControllerStub extends AbstractController
{
    /**
     * @param int    $someId
     * @param string $slug
     * @return ResponseInterface
     */
    public function fooFancyActionAction(int $someId, string $slug): ResponseInterface
    {
        return (new Response())
            ->withBody([
                'request' => $this->getRequest(),
                'routeMatch' => $this->getRouteMatch(),
                'someId' => $someId,
                'slug' => $slug,
            ]);
    }
}
