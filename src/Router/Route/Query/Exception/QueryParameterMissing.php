<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;
use LogicException;

class QueryParameterMissing extends LogicException implements RouteException
{
    /**
     * RequiredParameterMissing constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct(sprintf(
            'Query string parameter "%s" value is required.',
            $path
        ));
    }
}
