<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Chain;

use ExtendsFramework\Http\Message\RequestInterface;
use ExtendsFramework\Http\Message\ResponseInterface;

interface MiddlewareChainInterface
{
    /**
     * Proceed middleware chain with $request.
     *
     * To avoid (serious) side effects, the chain SHOULD NOT be called more the once.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function proceed(RequestInterface $request): ResponseInterface;
}
