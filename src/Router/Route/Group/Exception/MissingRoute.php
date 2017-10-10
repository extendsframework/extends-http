<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\Group\GroupRouteException;

class MissingRoute extends Exception implements GroupRouteException
{
    /**
     * When route option is missing.
     */
    public function __construct()
    {
        parent::__construct('Route is required and must be set in options.');
    }
}
