<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Router;

use ExtendsFramework\Http\Controller\ControllerInterface;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\Exception\ExecutionFailed;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use Throwable;

class RouterMiddleware implements MiddlewareInterface
{
    /**
     * Router to route request.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * Service locator to get controller.
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Create a new router middleware.
     *
     * @param RouterInterface         $router
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(RouterInterface $router, ServiceLocatorInterface $serviceLocator)
    {
        $this->router = $router;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        try {
            $match = $this->router->route($request);
            if ($match) {
                $key = $match->getParameters()->get('controller');
                $controller = $this->getController($key);

                return $controller->dispatch($request);
            }
        } catch (Throwable $exception) {
            throw ExecutionFailed::fromThrowable($exception);
        }

        return $chain->proceed($request);
    }

    /**
     * Get controller for $key from the service locator.
     *
     * @param string $key
     * @return ControllerInterface
     */
    protected function getController(string $key): ControllerInterface
    {
        return $this->serviceLocator->get($key);
    }
}
