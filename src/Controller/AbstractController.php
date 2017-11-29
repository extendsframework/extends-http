<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use ExtendsFramework\Http\Controller\Exception\ActionNotFound;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ReflectionMethod;

abstract class AbstractController implements ControllerInterface
{
    /**
     * String to append to the action.
     *
     * @var string
     */
    protected $postfix = 'Action';

    /**
     * Dispatch request.
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Dispatch route match.
     *
     * @var RouteMatchInterface
     */
    protected $routeMatch;

    /**
     * @inheritDoc
     */
    public function dispatch(RequestInterface $request, RouteMatchInterface $routeMatch): ResponseInterface
    {
        $this->request = $request;
        $this->routeMatch = $routeMatch;

        $method = $this->getMethod($routeMatch);
        $parameters = $this->getParameters($method, $routeMatch);

        return $method->invokeArgs($this, $parameters);
    }

    /**
     * Get callable method for $action.
     *
     * The object property $postfix will be append to $action.
     *
     * @param RouteMatchInterface $routeMatch
     * @return ReflectionMethod
     * @throws ControllerException
     */
    protected function getMethod(RouteMatchInterface $routeMatch): ReflectionMethod
    {
        $action = $this->getAction($routeMatch);

        return new ReflectionMethod($this, $action . $this->postfix);
    }

    /**
     * Normalize action string.
     *
     * @param RouteMatchInterface $routeMatch
     * @return string
     * @throws ControllerException
     */
    protected function getAction(RouteMatchInterface $routeMatch): string
    {
        $parameters = $routeMatch->getParameters();
        if (array_key_exists('action', $parameters) === false) {
            throw new ActionNotFound();
        }

        return $this->normalizeAction($parameters['action']);
    }

    /**
     * Get route match parameter for $method.
     *
     * @param ReflectionMethod    $method
     * @param RouteMatchInterface $routeMatch
     * @return array
     */
    protected function getParameters(ReflectionMethod $method, RouteMatchInterface $routeMatch): array
    {
        $parameters = [];
        foreach ($method->getParameters() as $parameter) {
            $parameters[] = $routeMatch->getParameter($parameter->getName());
        }

        return $parameters;
    }

    /**
     * Normalize action string.
     *
     * @param string $action
     * @return string
     */
    protected function normalizeAction(string $action): string
    {
        $action = strtolower($action);
        $action = str_replace(['_', '-', '.'], ' ', $action);
        $action = ucwords($action);
        $action = str_replace(' ', '', $action);
        $action = lcfirst($action);

        return $action;
    }

    /**
     * Get request.
     *
     * @return RequestInterface
     */
    protected function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Get route match.
     *
     * @return RouteMatchInterface
     */
    protected function getRouteMatch(): RouteMatchInterface
    {
        return $this->routeMatch;
    }
}
