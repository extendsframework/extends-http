<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use ExtendsFramework\Container\ContainerException;
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
        if (!\method_exists($this, $method)) {
            throw ControllerException::forMethodNotFound($action);
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
        try {
            $action = $request->getAttributes()->get('action');
        } catch (ContainerException $exception) {
            throw ControllerException::forActionNotFound($exception);
        }

        $action = str_replace(['_', '-', '.'], ' ', strtolower($action));
        $action = ucwords($action);

        return lcfirst(str_replace(' ', '', $action));
    }
}
