<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\ServiceLocator\Factory;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChain;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class MiddlewareChainFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): MiddlewareChainInterface
    {
        $config = $serviceLocator->getConfig();

        $chain = new MiddlewareChain();
        foreach ($config[MiddlewareChainInterface::class] ?? [] as $middleware => $priority) {
            $chain->addMiddleware(
                $serviceLocator->getService($middleware),
                $priority
            );
        }

        return $chain;
    }
}
