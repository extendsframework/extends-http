<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Scheme\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;

class InvalidOptions extends RouteException
{
    /**
     * Returns a new instance when required scheme option is missing.
     *
     * @return RouteException
     */
    public static function forMissingScheme(): RouteException
    {
        return new static('Scheme is required and MUST be set in options.');
    }
}
