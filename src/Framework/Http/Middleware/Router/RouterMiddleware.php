<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\Http\Middleware\Router;

use ExtendsFramework\Http\Controller\ControllerException;
use ExtendsFramework\Http\Controller\ControllerInterface;
use ExtendsFramework\Http\Framework\Http\Middleware\Router\Exception\ControllerDispatchFailed;
use ExtendsFramework\Http\Framework\Http\Middleware\Router\Exception\ControllerNotFound;
use ExtendsFramework\Http\Framework\Http\Middleware\Router\Exception\ControllerParameterMissing;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

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
        $match = $this->router->route($request);
        if ($match instanceof RouteMatchInterface) {
            $parameters = $match->getParameters();
            if (array_key_exists('controller', $parameters) === false) {
                throw new ControllerParameterMissing();
            }

            try {
                $controller = $this->getController($parameters['controller']);
            } catch (ServiceLocatorException $exception) {
                throw new ControllerNotFound($parameters['controller'], $exception);
            }

            try {
                return $controller->dispatch($request, $match);
            } catch (ControllerException $exception) {
                throw new ControllerDispatchFailed($exception);
            }
        }

        return $chain->proceed($request);
    }

    /**
     * Get controller for $key from the service locator.
     *
     * @param string $key
     * @return ControllerInterface
     * @throws ServiceLocatorException
     */
    protected function getController(string $key): ControllerInterface
    {
        return $this->serviceLocator->getService($key);
    }
}
