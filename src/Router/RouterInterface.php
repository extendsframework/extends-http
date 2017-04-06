<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

interface RouterInterface
{
    /**
     * Route $request to corresponding controller.
     *
     * When $request can not be matched, null will be returned.
     *
     * @param RequestInterface $request
     * @return RouteMatchInterface
     * @throws RouterException
     */
    public function route(RequestInterface $request): ?RouteMatchInterface;
}
