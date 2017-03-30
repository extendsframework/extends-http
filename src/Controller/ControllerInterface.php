<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;

interface ControllerInterface
{
    /**
     * Dispatch $request to controller $action.
     *
     * Method must return result as an array. When there is no result to result, this method must return an empty
     * array. When no method can be found, an exception will be thrown.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ControllerException
     */
    public function dispatch(RequestInterface $request): ResponseInterface;
}