<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Http\Router\Route\Group\GroupRoute;
use ExtendsFramework\Http\Router\Route\Path\PathRoute;

class RouteFactory implements RouteFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $config): RouteInterface
    {
        $route = $config['type'] ?? PathRoute::class;
        if (is_subclass_of($route, RouteInterface::class, true)) {
            $route = $route::factory($config['options'] ?? []);
        }

        if (!$route instanceof RouteInterface) {
            throw RouteException::forInvalidRouteType($route);
        }

        $children = $config['children'] ?? [];
        if (!empty($children)) {
            $route = $this->createGroupRoute($route, $children, $config['abstract'] ?? true);
        }

        return $route;
    }

    /**
     * Create new group route with $route from $config.
     *
     * @param RouteInterface $route
     * @param array          $children
     * @param bool|null      $abstract
     * @return RouteInterface
     * @throws RouteException
     */
    protected function createGroupRoute(RouteInterface $route, array $children, bool $abstract): RouteInterface
    {
        $routes = [];
        foreach ($children as $child) {
            $routes[] = $this->create($child);
        }

        return new GroupRoute(
            $route,
            $routes,
            $abstract
        );
    }
}
