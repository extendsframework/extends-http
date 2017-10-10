<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\Query\QueryRouteException;

class MissingConstraints extends Exception implements QueryRouteException
{
    /**
     * When constraints are missing in options.
     */
    public function __construct()
    {
        parent::__construct('Constraints are required and must be set in options.');
    }
}
