<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Loader;

use ExtendsFramework\Http\Framework\Http\Middleware\Controller\ControllerMiddleware;
use ExtendsFramework\Http\Framework\Http\Middleware\Exception\ExceptionMiddleware;
use ExtendsFramework\Http\Framework\Http\Middleware\Renderer\RendererMiddleware;
use ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware;
use ExtendsFramework\Http\Framework\ServiceLocator\Factory\MiddlewareChainFactory;
use ExtendsFramework\Http\Framework\ServiceLocator\Factory\RouterFactory;
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
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
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
                    RouterInterface::class => RouterFactory::class,
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
                    RouterMiddleware::class => RouterMiddleware::class,
                    ControllerMiddleware::class => ControllerMiddleware::class,
                    ExceptionMiddleware::class => ExceptionMiddleware::class,
                ],
            ],
            MiddlewareChainInterface::class => [
                RendererMiddleware::class => 200,
                ExceptionMiddleware::class => 190,
                RouterMiddleware::class => 150,
                ControllerMiddleware::class => 100,
            ],
            RouterInterface::class => [
                'routes' => [],
            ],
        ];
    }
}
