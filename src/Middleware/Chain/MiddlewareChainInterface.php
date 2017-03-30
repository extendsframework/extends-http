<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Chain;

use ExtendsFramework\Http\Middleware\MiddlewareException;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;

interface MiddlewareChainInterface
{
    /**
     * Proceed middleware chain with $request.
     *
     * To avoid (serious) side effects, the chain SHOULD NOT be called more the once.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws MiddlewareException
     */
    public function proceed(RequestInterface $request): ResponseInterface;
}
