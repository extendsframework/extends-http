<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Container\ContainerInterface;

interface RouteMatchInterface
{
    /**
     * Get merged parameters from route.
     *
     * @return ContainerInterface
     */
    public function getParameters(): ContainerInterface;

    /**
     * Get request URI path offset.
     *
     * @return int
     */
    public function getPathOffset(): int;

    /**
     * Merge with other $routeMatch.
     *
     * Used for nested routes.
     *
     * @param RouteMatchInterface $routeMatch
     * @return RouteMatchInterface
     */
    public function merge(RouteMatchInterface $routeMatch): RouteMatchInterface;
}
