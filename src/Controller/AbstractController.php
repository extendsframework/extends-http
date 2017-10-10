<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use ExtendsFramework\Http\Controller\Exception\ActionNotFound;
use ExtendsFramework\Http\Controller\Exception\MethodNotFound;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;

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
    public function dispatch(RequestInterface $request): ResponseInterface
    {
        $method = $this->getMethod($request);

        return $method($request);
    }

    /**
     * Get callable method for $action.
     *
     * The object property $postfix will be append to $action. When no method can be found for $action, an exception
     * will be thrown.
     *
     * @param RequestInterface $request
     * @return callable
     * @throws ControllerException
     */
    protected function getMethod(RequestInterface $request): callable
    {
        $action = $this->getAction($request);
        $method = $action . $this->postfix;
        if (method_exists($this, $method) === false) {
            throw new MethodNotFound($action);
        }

        return [$this, $method];
    }

    /**
     * Normalize action string.
     *
     * @param RequestInterface $request
     * @return string
     * @throws ControllerException
     */
    protected function getAction(RequestInterface $request): string
    {
        $attributes = $request->getAttributes();
        if (array_key_exists('action', $attributes) === false) {
            throw new ActionNotFound();
        }

        return $this->normalizeAction($attributes['action']);
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
