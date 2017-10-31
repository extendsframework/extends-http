<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\Http\Middleware\Router;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\RouterInterface;

class RouterMiddleware implements MiddlewareInterface
{
    /**
     * Router to route request.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * Create a new router middleware.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $match = $this->router->route($request);
        if ($match instanceof RouteMatchInterface) {
            $request = $request->withRouteMatch($match);
        }

        return $chain->proceed($request);
    }
}
