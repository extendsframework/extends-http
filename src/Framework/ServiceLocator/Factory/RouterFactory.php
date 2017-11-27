<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Factory;

use ExtendsFramework\Http\Router\Route\Group\GroupRoute;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Router;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class RouterFactory implements ServiceFactoryInterface
{
    /**
     * Create router.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|null              $extra
     * @return RouterInterface
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): RouterInterface
    {
        $config = $serviceLocator->getConfig();
        $config = $config[RouterInterface::class] ?? [];

        $router = new Router();
        foreach ($config['routes'] ?? [] as $name => $config) {
            $router->addRoute(
                $this->createRoute($serviceLocator, $config),
                $name
            );
        }

        return $router;
    }

    /**
     * Create RouterInterface from $config.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param array                   $config
     * @return RouteInterface
     * @throws ServiceLocatorException
     */
    protected function createRoute(ServiceLocatorInterface $serviceLocator, array $config): RouteInterface
    {
        $route = $serviceLocator->getService($config['name'], $config['options'] ?? []);
        if (array_key_exists('children', $config) === true) {
            $route = $this->createGroup($serviceLocator, $route, $config['children'], $config['abstract'] ?? null);
        }

        return $route;
    }

    /**
     * Create group route.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param RouteInterface          $route
     * @param array                   $children
     * @param bool|null               $abstract
     * @return RouteInterface
     * @throws ServiceLocatorException
     */
    protected function createGroup(ServiceLocatorInterface $serviceLocator, RouteInterface $route, array $children, bool $abstract = null): RouteInterface
    {
        /** @var GroupRoute $group */
        $group = $serviceLocator->getService(GroupRoute::class, [
            'route' => $route,
            'abstract' => $abstract,
        ]);

        foreach ($children as $name => $child) {
            $group->addRoute(
                $this->createRoute($serviceLocator, $child),
                $name
            );
        }

        return $group;
    }
}
