<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Factory;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChain;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class MiddlewareChainFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $config = $serviceLocator->getConfig();

        $chain = new MiddlewareChain();
        foreach ($config[MiddlewareChainInterface::class] ?? [] as $middleware => $priority) {
            $chain->addMiddleware(
                $this->getMiddleware($serviceLocator, $middleware),
                $priority
            );
        }

        return $chain;
    }

    /**
     * Get middleware for key.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $key
     * @return MiddlewareInterface
     * @throws ServiceLocatorException
     */
    protected function getMiddleware(ServiceLocatorInterface $serviceLocator, string $key): object
    {
        return $serviceLocator->getService($key);
    }
}
