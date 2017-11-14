<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Exception;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\RouterException;
use LogicException;

class NotFound extends LogicException implements RouterException
{
    public function __construct(RequestInterface $request)
    {
    }
}
