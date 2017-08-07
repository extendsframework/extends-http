<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

interface RouteFactoryInterface
{
    /**
     * Create route from $config.
     *
     * @param array $config
     * @return RouteInterface
     * @throws RouteException
     */
    public function create(array $config): RouteInterface;
}
