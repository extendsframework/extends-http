<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Http\Router\Route\Exception\InvalidRouteType;
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
        if (is_subclass_of($route, RouteInterface::class, true) === true) {
            $route = $route::factory($config['options'] ?? []);
        }

        if (!$route instanceof RouteInterface) {
            throw new InvalidRouteType($route);
        }

        $children = $config['children'] ?? [];
        if (empty($children) === false) {
            $route = $this->createGroupRoute($route, $children, $config['abstract'] ?? true);
        }

        return $route;
    }

    /**
     * Create new group route with $route from $config.
     *
     * @param RouteInterface $route
     * @param array          $children
     * @param bool           $abstract
     * @return RouteInterface
     * @throws RouteException
     */
    protected function createGroupRoute(RouteInterface $route, array $children, bool $abstract): RouteInterface
    {
        $group = new GroupRoute($route, $abstract);
        foreach ($children as $child) {
            $group->addChild($this->create($child));
        }

        return $group;
    }
}
