<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Server;

use Exception;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareException;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Logger\LoggerInterface;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    /**
     * Run.
     *
     * Test that middleware will be called with request.
     *
     * @covers \ExtendsFramework\Http\Server\Server::__construct()
     * @covers \ExtendsFramework\Http\Server\Server::run()
     */
    public function testRun(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $middlewareChain = $this->createMock(MiddlewareChainInterface::class);
        $middlewareChain
            ->expects($this->once())
            ->method('proceed')
            ->with($request);

        /**
         * @var MiddlewareChainInterface $middlewareChain
         * @var RequestInterface         $request
         * @var LoggerInterface          $logger
         */
        $server = new Server($middlewareChain, $request, $logger);
        $server->run();
    }

    /**
     * Log.
     *
     * Test that log will be written when middleware chain proceeding fails.
     *
     * @covers \ExtendsFramework\Http\Server\Server::__construct()
     * @covers \ExtendsFramework\Http\Server\Server::run()
     */
    public function testLog(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('log')
            ->with('Middleware proceed failed!');

        $request = $this->createMock(RequestInterface::class);

        $middlewareChain = $this->createMock(MiddlewareChainInterface::class);
        $middlewareChain
            ->expects($this->once())
            ->method('proceed')
            ->willThrowException(new MiddlewareExceptionStub('Middleware proceed failed!'));

        /**
         * @var MiddlewareChainInterface $middlewareChain
         * @var RequestInterface         $request
         * @var LoggerInterface          $logger
         */
        $server = new Server($middlewareChain, $request, $logger);
        $server->run();
    }
}

class MiddlewareExceptionStub extends Exception implements MiddlewareException
{
}
