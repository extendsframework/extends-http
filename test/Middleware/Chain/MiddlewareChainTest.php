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
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::__construct()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::addMiddleware()
     * @covers \ExtendsFramework\Http\Middleware\Chain\MiddlewareChain::proceed()
     */
    public function testCanProceedRequestAndReturnResponse(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $middleware1 = new class implements MiddlewareInterface
        {
            public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
            {
                return $chain->proceed($request);
            }
        };

        $middleware2 = new class implements MiddlewareInterface
        {
            public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
            {
                return new Response();
            }
        };

        /**
         * @var RequestInterface    $request
         * @var MiddlewareInterface $middleware1
         * @var MiddlewareInterface $middleware2
         */
        $chain = new MiddlewareChain();
        $response = $chain
            ->addMiddleware($middleware1)
            ->addMiddleware($middleware2)
            ->proceed($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
