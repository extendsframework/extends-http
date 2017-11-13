<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;
use LogicException;

class MethodNotAllowed extends LogicException implements RouteException
{
    /**
     * MethodNotAllowed constructor.
     *
     * @param string $method
     */
    public function __construct(string $method)
    {
        parent::__construct(sprintf(
            'Method "%s" is not allowed.',
            $method
        ));
    }
}
