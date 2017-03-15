<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Chain;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Http\Message\RequestInterface;
use ExtendsFramework\Http\Message\Response;
use ExtendsFramework\Http\Message\ResponseInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
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
                $response = new Response();
                $response = $response->withBody(new Container([
                    'foo' => 'bar',
                ]));

                /** @var ResponseInterface $response */
                return $response;
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
