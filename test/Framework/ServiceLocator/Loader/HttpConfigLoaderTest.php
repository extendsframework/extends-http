<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Loader;

use ExtendsFramework\Http\Framework\ServiceLocator\Factory\MiddlewareChainFactory;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\Request;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class HttpConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsFramework\Http\Framework\ServiceLocator\Loader\HttpConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new HttpConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    MiddlewareChainInterface::class => MiddlewareChainFactory::class,
                ],
                StaticFactoryResolver::class => [
                    RequestInterface::class => Request::class,
                    ResponseInterface::class => Response::class,
                ],
            ],
        ], $loader->load());
    }
}
