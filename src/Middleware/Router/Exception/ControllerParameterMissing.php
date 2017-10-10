<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Router\Exception;

use Exception;
use ExtendsFramework\Http\Middleware\Router\RouterMiddlewareException;

class ControllerParameterMissing extends Exception implements RouterMiddlewareException
{
    /**
     * When controller key is not set in parameters.
     */
    public function __construct()
    {
        parent::__construct('Controller key is not set in route match parameters.');
    }
}
