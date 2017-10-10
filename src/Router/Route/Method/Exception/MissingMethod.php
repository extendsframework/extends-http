<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\Method\MethodRouteException;

class MissingMethod extends Exception implements MethodRouteException
{
    /**
     * When method is missing in options.
     */
    public function __construct()
    {
        parent::__construct('Method is required and must be set in options.');
    }
}
