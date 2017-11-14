<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Exception\NotFound;
use ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class Router implements RouterInterface
{
    /**
     * Routes to match.
     *
     * @var RouteInterface[]
     */
    protected $routes = [];

    /**
     * @inheritDoc
     */
    public function route(RequestInterface $request): RouteMatchInterface
    {
        $notAllowed = null;
        foreach ($this->routes as $route) {
            try {
                $match = $route->match($request, 0);
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

        throw new NotFound($request);
    }

    /**
     * Add $route to router with $priority.
     *
     * @param RouteInterface $route
     * @return Router
     */
    public function addRoute(RouteInterface $route): Router
    {
        $this->routes[] = $route;

        return $this;
    }
}
