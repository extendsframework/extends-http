<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;

class InvalidOptions extends RouteException
{
    /**
     * Returns a new instance when required route option is missing.
     *
     * @return RouteException
     */
    public static function forMissingRoute(): RouteException
    {
        return new static('Route is required and MUST be set in options.');
    }

    /**
     * Returns a new instance when required children option is missing.
     *
     * @return RouteException
     */
    public static function forMissingChildren(): RouteException
    {
        return new static('Children are required and MUST be set in options.');
    }
}
