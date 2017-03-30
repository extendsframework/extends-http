<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Path\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;

class InvalidOptions extends RouteException
{
    /**
     * Returns a new instance when required path option is missing.
     *
     * @return RouteException
     */
    public static function forMissingPath(): RouteException
    {
        return new static('Path is required and MUST be set in options.');
    }
}
