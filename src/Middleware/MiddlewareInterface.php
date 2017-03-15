<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware;

use ExtendsFramework\Http\Message\RequestInterface;
use ExtendsFramework\Http\Message\ResponseInterface;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;

interface MiddlewareInterface
{
    /**
     * Process middleware.
     *
     * The middleware MUST call proceed() on $chain with a RequestInterface object. It is RECOMMENDED to use $request
     * for this call.
     *
     * The middleware MUST return a ResponseInterface object. It is RECOMMENDED to return the response from the $chain
     * proceed() method.
     *
     * Both request and response MAY be modified by creating a new instance.
     *
     * @param RequestInterface         $request
     * @param MiddlewareChainInterface $chain
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface;
}
