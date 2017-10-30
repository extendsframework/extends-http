<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use ExtendsFramework\Http\Controller\Exception\ActionNotFound;
use ExtendsFramework\Http\Controller\Exception\MethodNotFound;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

abstract class AbstractController implements ControllerInterface
{
    /**
     * String to append to the action.
     *
     * @var string
     */
    protected $postfix = 'Action';

    /**
     * @inheritDoc
     */
    public function dispatch(RequestInterface $request, RouteMatchInterface $routeMatch): ResponseInterface
    {
        $method = $this->getMethod($routeMatch);

        return $method($request);
    }

    /**
     * Get callable method for $action.
     *
     * The object property $postfix will be append to $action. When no method can be found for $action, an exception
     * will be thrown.
     *
     * @param RouteMatchInterface $routeMatch
     * @return callable
     * @throws ControllerException
     */
    protected function getMethod(RouteMatchInterface $routeMatch): callable
    {
        $action = $this->getAction($routeMatch);
        $method = $action . $this->postfix;
        if (method_exists($this, $method) === false) {
            throw new MethodNotFound($action);
        }

        return [$this, $method];
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
}
