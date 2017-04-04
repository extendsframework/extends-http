<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;

class InvalidOptions extends RouteException
{
    /**
     * Returns a new instance when required path option is missing.
     *
     * @return RouteException
     */
    public static function forMissingConstraints(): RouteException
    {
        return new static('Constraints are required and MUST be set in options.');
    }
}
