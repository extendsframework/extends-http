<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Exception;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\RouterException;
use LogicException;

class NotFound extends LogicException implements RouterException
{
    /**
     * NotFound constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct('Request could not be matched by a route.');
    }
}
