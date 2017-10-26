<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Server;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareException;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Logger\LoggerInterface;

class Server implements ServerInterface
{
    /**
     * Middleware chain.
     *
     * @var MiddlewareChainInterface
     */
    protected $chain;

    /**
     * Request.
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Server constructor.
     *
     * @param MiddlewareChainInterface $chain
     * @param RequestInterface         $request
     * @param LoggerInterface          $logger
     */
    public function __construct(MiddlewareChainInterface $chain, RequestInterface $request, LoggerInterface $logger)
    {
        $this->chain = $chain;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        try {
            $this->chain->proceed($this->request);
        } catch (MiddlewareException $exception) {
            $this->logger->log($exception->getMessage());
        }
    }
}
