<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Chain;

use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use SplPriorityQueue;

class MiddlewareChain implements MiddlewareChainInterface
{
    /**
     * Middleware queue.
     *
     * @var SplPriorityQueue
     */
    private $queue;

    /**
     * Set priority queue.
     *
     * @param SplPriorityQueue $queue
     */
    public function __construct(SplPriorityQueue $queue = null)
    {
        $this->queue = $queue ?: new SplPriorityQueue();
    }

    /**
     * Clone middleware chain.
     *
     * Make sure the same queue is not referenced from the cloned middleware chain. Middlewares inside the queue are not
     * cloned. Be sure they are stateless because they can be called more than once.
     *
     * @return void
     */
    public function __clone()
    {
        $this->queue = clone $this->queue;
    }

    /**
     * @inheritDoc
     */
    public function proceed(RequestInterface $request): ResponseInterface
    {
        $middleware = $this->queue->current();
        if ($middleware instanceof MiddlewareInterface) {
            $this->queue->next();
            $response = $middleware->process($request, $this);
        }

        return $response ?? null;
    }

    /**
     * Add $middleware to the chain.
     *
     * When no $priority is given, 1 will be used. Middlewares with the same $priority will be processed randomly.
     *
     * @param MiddlewareInterface $middleware
     * @param int                 $priority
     * @return MiddlewareChain
     */
    public function addMiddleware(MiddlewareInterface $middleware, int $priority = null): MiddlewareChain
    {
        $this->queue->insert($middleware, $priority ?: 1);

        return $this;
    }
}
