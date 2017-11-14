<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;
use InvalidArgumentException;

class InvalidQueryString extends InvalidArgumentException implements RouteException
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
