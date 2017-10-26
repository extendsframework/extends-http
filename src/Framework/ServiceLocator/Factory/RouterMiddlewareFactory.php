<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Factory;

use ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class RouterMiddlewareFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): MiddlewareInterface
    {
        return new RouterMiddleware(
            $serviceLocator->getService(RouterInterface::class),
            $serviceLocator
        );
    }
}
