<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Loader;

use ExtendsFramework\Http\Framework\Http\Middleware\Exception\ExceptionMiddleware;
use ExtendsFramework\Http\Framework\Http\Middleware\Renderer\RendererMiddleware;
use ExtendsFramework\Http\Framework\ServiceLocator\Factory\MiddlewareChainFactory;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Renderer\Json\JsonRenderer;
use ExtendsFramework\Http\Renderer\RendererInterface;
use ExtendsFramework\Http\Request\Request;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Router\Route\Group\GroupRoute;
use ExtendsFramework\Router\Route\Host\HostRoute;
use ExtendsFramework\Router\Route\Method\MethodRoute;
use ExtendsFramework\Router\Route\Path\PathRoute;
use ExtendsFramework\Router\Route\Query\QueryRoute;
use ExtendsFramework\Router\Route\Scheme\SchemeRoute;
use ExtendsFramework\Router\RouterInterface;
use ExtendsFramework\Server\Server;
use ExtendsFramework\Server\ServerInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
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
                    GroupRoute::class => GroupRoute::class,
                    HostRoute::class => HostRoute::class,
                    MethodRoute::class => MethodRoute::class,
                    PathRoute::class => PathRoute::class,
                    QueryRoute::class => QueryRoute::class,
                    SchemeRoute::class => SchemeRoute::class,
                ],
                InvokableResolver::class => [
                    RendererInterface::class => JsonRenderer::class,
                ],
                ReflectionResolver::class => [
                    ServerInterface::class => Server::class,
                    RendererMiddleware::class => RendererMiddleware::class,
                    ExceptionMiddleware::class => ExceptionMiddleware::class,
                ],
            ],
            MiddlewareChainInterface::class => [
                RendererMiddleware::class => 200,
                ExceptionMiddleware::class => 190,
            ],
            RouterInterface::class => [
                'routes' => [],
            ],
        ], $loader->load());
    }
}
