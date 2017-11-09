<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

interface RouteMatchInterface
{
    /**
     * Get merged parameters from route.
     *
     * @return array
     */
    public function getParameters(): array;

    /**
     * Get parameter for $key.
     *
     * Default value $default will be returned when parameter for $key does not exists.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getParameter(string $key, $default = null);

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
