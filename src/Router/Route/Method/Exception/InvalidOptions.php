<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;

class InvalidOptions extends RouteException
{
    /**
     * Returns a new instance when required method option is missing.
     *
     * @return RouteException
     */
    public static function forMissingMethod(): RouteException
    {
        return new static('Method is required and MUST be set in options.');
    }
}
