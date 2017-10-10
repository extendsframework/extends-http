<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route;

use ExtendsFramework\Http\Request\RequestInterface;

interface RouteInterface
{
    /**
     * Match route against $request.
     *
     * Parameter $pathOffset is used to pass the request uri path offset to other routes.
     *
     * @param RequestInterface $request
     * @param int              $pathOffset
     * @return RouteMatchInterface
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface;

    /**
     * Create new route with given $options.
     *
     * When factory fails, an exception will be thrown.
     *
     * @param array $options
     * @return RouteInterface
     * @throws RouteException
     */
    public static function factory(array $options): RouteInterface;
}
