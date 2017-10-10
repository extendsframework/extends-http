<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Host\Exception;

use Exception;
use ExtendsFramework\Http\Router\Route\Host\HostRouteException;

class MissingHost extends Exception implements HostRouteException
{
    /**
     * When host option is missing.
     */
    public function __construct()
    {
        parent::__construct('Host is required and MUST be set in options.');
    }
}
