<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Host\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;

class InvalidOptions extends RouteException
{
    /**
     * Returns a new instance when required host option is missing.
     *
     * @return RouteException
     */
    public static function forMissingHost(): RouteException
    {
        return new static('Host is required and MUST be set in options.');
    }
}
