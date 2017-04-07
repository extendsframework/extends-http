<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Group\Exception\InvalidOptions;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class GroupRoute implements RouteInterface
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
    protected $children;

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
     * @param iterable       $children
     * @param bool           $abstract
     */
    public function __construct(RouteInterface $route, iterable $children, bool $abstract = null)
    {
        $this->route = $route;
        $this->abstract = $abstract ?? true;

        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (!isset($options['route'])) {
            throw InvalidOptions::forMissingRoute();
        }

        if (!isset($options['children'])) {
            throw InvalidOptions::forMissingChildren();
        }

        return new static(
            $options['route'],
            $options['children'],
            $options['abstract'] ?? null
        );
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

        foreach ($this->children as $child) {
            $childMatch = $child->match($request, $match->getPathOffset());
            if ($childMatch instanceof RouteMatchInterface) {
                return $match->merge($childMatch);
            }
        }

        if (!$this->abstract && $match) {
            return $match;
        }

        return null;
    }

    /**
     * Add a child route.
     *
     * @param RouteInterface $route
     * @return GroupRoute
     */
    protected function addChild(RouteInterface $route): GroupRoute
    {
        $this->children[] = $route;

        return $this;
    }
}
