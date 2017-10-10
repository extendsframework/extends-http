<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\RouteException;

class InvalidRouteType extends Exception implements RouteException
{
    /**
     * When $route is not an instance of RouteInterface.
     *
     * @param string $route
     */
    public function __construct($route)
    {
        parent::__construct(sprintf(
            'Route must be instance or subclass of RouteInterface, got "%s".',
            is_object($route) ? get_class($route) : gettype($route)
        ));
    }
}
