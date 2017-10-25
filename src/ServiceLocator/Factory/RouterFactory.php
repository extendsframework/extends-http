<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\ServiceLocator\Factory;

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
        foreach ($config['routes'] ?? [] as $route) {
            $router->addRoute(
                $serviceLocator->getService($route['name'], $route['options'] ?? [])
            );
        }

        return $router;
    }
}
