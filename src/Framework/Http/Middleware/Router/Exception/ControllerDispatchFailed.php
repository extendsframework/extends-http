<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\Http\Middleware\Router\Exception;

use Exception;
use ExtendsFramework\Http\Controller\ControllerException;
use ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddlewareException;

class ControllerDispatchFailed extends Exception implements RouterMiddlewareException
{
    /**
     * When controller dispatch throws $exception.
     *
     * @param ControllerException $exception
     */
    public function __construct(ControllerException $exception)
    {
        parent::__construct(
            'Failed to dispatch request to controller. See previous exception for more details.',
            0,
            $exception
        );
    }
}
