<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Exception\NotFound;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use SplPriorityQueue;

class Router implements RouterInterface
{
    /**
     * Routes to match.
     *
     * @var SplPriorityQueue
     */
    protected $routes;

    /**
     * Create a new router with $routes.
     *
     * @param SplPriorityQueue $routes
     */
    public function __construct(SplPriorityQueue $routes = null)
    {
        $this->routes = $routes ?: new SplPriorityQueue();
    }

    /**
     * @inheritDoc
     */
    public function route(RequestInterface $request): RouteMatchInterface
    {
        foreach (clone $this->routes as $route) {
            if ($route instanceof RouteInterface) {
                $match = $route->match($request, 0);
                if ($match instanceof RouteMatchInterface) {
                    return $match;
                }
            }
        }

        throw new NotFound($request);
    }

    /**
     * Add $route to router with $priority.
     *
     * Route with a higher $priority will be processed earlier.
     *
     * @param RouteInterface $route
     * @param int            $priority
     * @return Router
     */
    public function addRoute(RouteInterface $route, int $priority = null): Router
    {
        $this->routes->insert($route, $priority ?: 1);

        return $this;
    }
}
