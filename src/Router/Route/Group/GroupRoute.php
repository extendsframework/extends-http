<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Path\PathRoute;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\Routes;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class GroupRoute implements RouteInterface, StaticFactoryInterface
{
    use Routes;

    /**
     * If this can be matched.
     *
     * @var bool
     */
    protected $abstract;

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
        if ($route instanceof PathRoute) {
            $route->setStrict(false);
        }

        $this->route = $route;
        $this->abstract = $abstract ?? true;
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $outer = $this->route->match($request, $pathOffset);
        if (!$outer instanceof RouteMatchInterface) {
            return null;
        }

        $inner = $this->matchRoutes($request, $outer->getPathOffset());
        if ($inner instanceof RouteMatchInterface) {
            $outer = $outer->merge($inner);
            if ($outer->getPathOffset() === strlen($request->getUri()->getPath())) {
                return $outer;
            }
        }

        if ($this->abstract === false) {
            return $outer;
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
}
