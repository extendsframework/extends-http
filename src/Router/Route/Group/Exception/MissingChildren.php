<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Group\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\Group\GroupRouteException;

class MissingChildren extends Exception implements GroupRouteException
{
    /**
     * When children are missing.
     */
    public function __construct()
    {
        parent::__construct('Children are required and must be set in options.');
    }
}
