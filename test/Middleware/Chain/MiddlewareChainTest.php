<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Chain;

use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class MiddlewareChainTest extends TestCase
{
    /**
     * Proceed.
     *
     * Test that middleware chain is called and last middleware will return a response object.
     *
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::__construct()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::addMiddleware()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::proceed()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::getQueue()
     */
    public function testProceed(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $middleware1 = new class implements MiddlewareInterface
        {
            /**
             * @inheritDoc
             */
            public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
            {
                return $chain->proceed($request);
            }
        };

        $middleware2 = new class implements MiddlewareInterface
        {
            /**
             * @inheritDoc
             */
            public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
            {
                return new Response();
            }
        };

        /**
         * @var RequestInterface $request
         */
        $chain = new MiddlewareChain();
        $response = $chain
            ->addMiddleware($middleware1)
            ->addMiddleware($middleware2)
            ->proceed($request);

        $this->assertIsObject($response);
    }

    /**
     * Clone.
     *
     * Test that middleware queue will be cloned when cloning the middleware chain.
     *
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::__construct()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::__clone()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::addMiddleware()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::proceed()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::getQueue()
     */
    public function testClone(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $middleware1 = new class implements MiddlewareInterface
        {
            /**
             * @inheritDoc
             */
            public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
            {
                return $chain->proceed($request);
            }
        };

        $middleware2 = new class implements MiddlewareInterface
        {
            /**
             * @inheritDoc
             */
            public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
            {
                $cloned = clone $chain;

                $response = $chain->proceed($request);

                (clone $cloned)->proceed($request);
                (clone $cloned)->proceed($request);

                return $response;
            }
        };

        $middleware3 = $this->createMock(MiddlewareInterface::class);
        $middleware3
            ->expects($this->exactly(3))
            ->method('process')
            ->with($request, $this->isInstanceOf(MiddlewareChainInterface::class))
            ->willReturn($response);

        /**
         * @var RequestInterface    $request
         * @var MiddlewareInterface $middleware1
         * @var MiddlewareInterface $middleware3
         */
        $chain = new MiddlewareChain();

        $this->assertSame(
            $response,
            $chain
                ->addMiddleware($middleware1, 30)
                ->addMiddleware($middleware2, 20)
                ->addMiddleware($middleware3, 10)
                ->proceed($request)
        );
    }
}
