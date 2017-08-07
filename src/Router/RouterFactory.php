<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Router\Route\RouteFactoryInterface;

class RouterFactory implements RouterFactoryInterface
{
    /**
     * Factory to create a route.
     *
     * @var RouteFactoryInterface
     */
    protected $factory;

    /**
     * Create new instance with $factory.
     *
     * @param RouteFactoryInterface $factory
     */
    public function __construct(RouteFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function create(iterable $routes): RouterInterface
    {
        $router = new Router();

        foreach ($routes as $config) {
            $route = $this->factory->create($config);
            $router->addRoute($route, $config['priority'] ?? 1);
        }

        return $router;
    }
}
