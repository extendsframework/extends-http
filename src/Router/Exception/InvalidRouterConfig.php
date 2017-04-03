<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Exception;

use ExtendsFramework\Http\Router\RouterException;

class InvalidRouterConfig extends RouterException
{
    /**
     * Returns a new instance when $route type is invalid.
     *
     * @param mixed $route
     * @return RouterException
     */
    public static function forRouteType($route): RouterException
    {
        return new static(sprintf(
            'Route MUST be instance or subclass of RouteInterface, got "%s".',
            is_object($route) ? get_class($route) : gettype($route)
        ));
    }
}
