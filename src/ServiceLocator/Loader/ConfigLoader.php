<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\ServiceLocator\Loader;

use ExtendsFramework\Http\Server\Server;
use ExtendsFramework\Http\Server\ServerInterface;
use ExtendsFramework\Http\Server\Middleware\Logger\LoggerMiddleware;
use ExtendsFramework\Http\Server\Middleware\NotFound\NotFoundMiddleware;
use ExtendsFramework\Http\Server\Middleware\Renderer\RendererMiddleware;
use ExtendsFramework\Http\Server\Middleware\Router\RouterMiddleware;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Renderer\Json\JsonRenderer;
use ExtendsFramework\Http\Renderer\RendererInterface;
use ExtendsFramework\Http\Request\Request;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\Group\GroupRoute;
use ExtendsFramework\Http\Router\Route\Host\HostRoute;
use ExtendsFramework\Http\Router\Route\Method\MethodRoute;
use ExtendsFramework\Http\Router\Route\Path\PathRoute;
use ExtendsFramework\Http\Router\Route\Query\QueryRoute;
use ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\Http\ServiceLocator\Factory\MiddlewareChainFactory;
use ExtendsFramework\Http\ServiceLocator\Factory\RouterFactory;
use ExtendsFramework\Http\ServiceLocator\Factory\RouterMiddlewareFactory;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class ConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    RouterInterface::class => RouterFactory::class,
                    MiddlewareChainInterface::class => MiddlewareChainFactory::class,
                    RouterMiddleware::class => RouterMiddlewareFactory::class
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
                    NotFoundMiddleware::class => NotFoundMiddleware::class,
                    RendererInterface::class => JsonRenderer::class,
                ],
                ReflectionResolver::class => [
                    ServerInterface::class => Server::class,
                    LoggerMiddleware::class => LoggerMiddleware::class,
                    RendererMiddleware::class => RendererMiddleware::class,
                ],
            ],
            MiddlewareChainInterface::class => [
                RendererMiddleware::class => 10,
                LoggerMiddleware::class => 20,
                RouterMiddleware::class => 30,
                NotFoundMiddleware::class => 40,
            ],
            RouterInterface::class => [
                'routes' => [],
            ],
        ];
    }
}
