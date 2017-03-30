<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\NotFound;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class NotFoundMiddlewareTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Middleware\NotFound\NotFoundMiddleware::process()
     */
    public function testWillReturnResponseWith404StatusCode(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new NotFoundMiddleware();
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }
}
