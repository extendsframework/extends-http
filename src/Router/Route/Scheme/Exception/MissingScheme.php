<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Scheme\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\Scheme\SchemeRouteException;

class MissingScheme extends Exception implements SchemeRouteException
{
    /**
     * When scheme is missing in options.
     */
    public function __construct()
    {
        parent::__construct('Scheme is required and must be set in options.');
    }
}
