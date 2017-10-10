<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

interface RouterFactoryInterface
{
    /**
     * Create new router from $routes.
     *
     * @param array $routes
     * @return RouterInterface
     * @throws RouterException
     */
    public function create(array $routes): RouterInterface;
}
