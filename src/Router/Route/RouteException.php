<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Http\Router\RouterException;

class RouteException extends RouterException
{
    /**
     * Returns a new instance when $route is not a sub class of RouteInterface.
     *
     * @param mixed $route
     * @return RouteException
     */
    public static function forInvalidRouteType($route): RouteException
    {
        return new static(sprintf(
            'Route MUST be instance or subclass of RouteInterface, got "%s".',
            is_object($route) ? get_class($route) : gettype($route)
        ));
    }
}
