<?php
declare(strict_types=1);


namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Group\GroupRoute;
use ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

trait Routes
{
    /**
     * Routes.
     *
     * @var RouteInterface[]
     */
    protected $routes = [];

    /**
     * Add $route to routes.
     *
     * @param RouteInterface $route
     * @return $this
     */
    public function addRoute(RouteInterface $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Route $request to child routes with $pathOffset.
     *
     *
     *
     * @param RequestInterface $request
     * @param int              $pathOffset
     * @return RouteMatchInterface|null
     * @throws Route\RouteException
     */
    protected function matchRoutes(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $notAllowed = null;
        $routes = $this->getRoutes();
        foreach ($routes as $route) {
            try {
                $match = $route->match($request, $pathOffset);
                if ($match instanceof RouteMatchInterface) {
                    return $match;
                }
            } catch (MethodNotAllowed $exception) {
                if ($notAllowed instanceof MethodNotAllowed) {
                    $notAllowed->addAllowedMethods($exception->getAllowedMethods());
                } else {
                    $notAllowed = $exception;
                }
            }
        }

        if ($notAllowed instanceof MethodNotAllowed) {
            throw $notAllowed;
        }

        return null;
    }

    /**
     * Get routes.
     *
     * Sort children that group routes will be matched first, nested routes before flat routes.
     *
     * @return RouteInterface[]
     */
    protected function getRoutes(): array
    {
        usort($this->routes, function (RouteInterface $left, RouteInterface $right) {
            if ($left instanceof GroupRoute || $right instanceof GroupRoute) {
                return 1;
            }

            return 0;
        });

        return $this->routes;
    }
}
