<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\Http\Middleware\Renderer;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Renderer\RendererInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class RendererMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that renderer will be called with response from next in chain.
     *
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Renderer\RendererMiddleware::__construct()
     * @covers \ExtendsFramework\Http\Framework\Http\Middleware\Renderer\RendererMiddleware::process()
     */
    public function testProcess(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $renderer = $this->createMock(RendererInterface::class);
        $renderer
            ->expects($this->once())
            ->method('render')
            ->with($response);

        /**
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         * @var RendererInterface        $renderer
         */
        $middleware = new RendererMiddleware($renderer);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
