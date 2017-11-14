<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Exception\NotFound;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class Router implements RouterInterface
{
    use Routes;

    /**
     * @inheritDoc
     */
    public function route(RequestInterface $request): RouteMatchInterface
    {
        $match = $this->matchRoutes($request, 0);
        if ($match instanceof RouteMatchInterface) {
            return $match;
        }

        throw new NotFound($request);
    }
}
