<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Router\Exception\InvalidRouterConfig;
use ExtendsFramework\Http\Router\Route\Group\GroupRoute;
use ExtendsFramework\Http\Router\Route\Path\PathRoute;
use ExtendsFramework\Http\Router\Route\RouteInterface;

class RouterFactory implements RouterFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(iterable $routes): RouterInterface
    {
        $router = new Router();

        foreach ($routes as $key => $config) {
            $route = $this->createRoute($config);
            $router->addRoute($route, $config['priority'] ?? 1);
        }

        return $router;
    }

    /**
     * Get route from $config.
     *
     * @param array $config
     * @return RouteInterface
     * @throws RouterException
     */
    protected function createRoute(array $config): RouteInterface
    {
        $route = $config['type'] ?? PathRoute::class;
        if (is_subclass_of($route, RouteInterface::class, true)) {
            $route = $route::factory($config['options'] ?? []);
        }

        if (!$route instanceof RouteInterface) {
            throw InvalidRouterConfig::forRouteType($route);
        }

        $children = $config['children'] ?? [];
        if (!empty($children)) {
            $route = new GroupRoute(
                $route,
                array_map([$this, 'createRoute'], $children),
                $config['abstract'] ?? false
            );
        }

        return $route;
    }
}
