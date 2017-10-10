<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Path\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\Path\PathRouteException;

class MissingPath extends Exception implements PathRouteException
{
    /**
     * When path is missing in options.
     */
    public function __construct()
    {
        parent::__construct('Path is required and must be set in options.');
    }
}
