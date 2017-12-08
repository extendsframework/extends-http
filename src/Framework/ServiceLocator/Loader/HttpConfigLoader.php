<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Loader;

use ExtendsFramework\Http\Framework\ServiceLocator\Factory\MiddlewareChainFactory;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\Request;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class HttpConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    MiddlewareChainInterface::class => MiddlewareChainFactory::class,
                ],
                StaticFactoryResolver::class => [
                    RequestInterface::class => Request::class,
                    ResponseInterface::class => Response::class,
                ],
            ],
        ];
    }
}
