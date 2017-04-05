<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Container\ContainerInterface;

class RouteMatch implements RouteMatchInterface
{
    /**
     * Matched parameters.
     *
     * @var ContainerInterface
     */
    protected $parameters;

    /**
     * Request URI path offset.
     *
     * @var int
     */
    protected $pathOffset;

    /**
     * Create a route match.
     *
     * @param ContainerInterface $parameters
     * @param int                $pathOffset
     */
    public function __construct(ContainerInterface $parameters, int $pathOffset = null)
    {
        $this->parameters = $parameters;
        $this->pathOffset = $pathOffset ?: 0;
    }

    /**
     * @inheritDoc
     */
    public function getPathOffset(): int
    {
        return $this->pathOffset;
    }

    /**
     * @inheritDoc
     */
    public function getParameters(): ContainerInterface
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function merge(RouteMatchInterface $routeMatch): RouteMatchInterface
    {
        $merged = clone $this;
        $merged->parameters = $this->getParameters()->merge($routeMatch->getParameters());
        $merged->pathOffset += $routeMatch->getPathOffset();

        return $merged;
    }
}
