<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class GroupRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * If this can be matched.
     *
     * @var bool
     */
    protected $abstract;

    /**
     * Child routes to match.
     *
     * @var RouteInterface[]
     */
    protected $children = [];

    /**
     * Route to match.
     *
     * @var RouteInterface
     */
    protected $route;

    /**
     * Create a group route.
     *
     * @param RouteInterface $route
     * @param bool           $abstract
     */
    public function __construct(RouteInterface $route, bool $abstract = null)
    {
        $this->route = $route;
        $this->abstract = $abstract ?? true;
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $match = $this->route->match($request, $pathOffset);
        if (!$match instanceof RouteMatchInterface) {
            return null;
        }

        $notAllowed = null;
        foreach ($this->children as $index => $child) {
            try {
                $childMatch = $child->match($request, $match->getPathOffset());
                if ($childMatch instanceof RouteMatchInterface) {
                    return $match->merge($childMatch);
                }
            } catch (MethodNotAllowed $exception) {
                if ($notAllowed instanceof MethodNotAllowed) {
                    $notAllowed->addAllowedMethods($exception->getAllowedMethods());
                } else {
                    $notAllowed = $exception;
                }
            }
        }

        if ($this->abstract === false) {
            return $match;
        }

        if ($notAllowed instanceof MethodNotAllowed) {
            throw $notAllowed;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): RouteInterface
    {
        return new static($extra['route'], $extra['abstract'] ?? null);
    }

    /**
     * Add a child route.
     *
     * @param RouteInterface $route
     * @return GroupRoute
     */
    public function addChild(RouteInterface $route): GroupRoute
    {
        $this->children[] = $route;

        return $this;
    }
}
