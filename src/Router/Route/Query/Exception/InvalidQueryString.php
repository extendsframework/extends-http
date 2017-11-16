<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query\Exception;

use ExtendsFramework\Http\Router\Route\Query\QueryRouteException;
use InvalidArgumentException;

class InvalidQueryString extends InvalidArgumentException implements QueryRouteException
{
    /**
     * InvalidParameterValue constructor.
     *
     * @param string $parameter
     * @param string $value
     * @param string $constraint
     */
    public function __construct(string $parameter, string $value, string $constraint)
    {
        parent::__construct(sprintf(
            'Query string parameter "%s" value "%s" does not match constraint "%s".',
            $parameter,
            $value,
            $constraint
        ));
    }
}
