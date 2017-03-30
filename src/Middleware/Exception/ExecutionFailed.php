<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Middleware\Exception;

use ExtendsFramework\Http\Middleware\MiddlewareException;
use Throwable;

class ExecutionFailed extends MiddlewareException
{
    /**
     * Returns a new instance when $exception is caught will processing middleware.
     *
     * @param Throwable $exception
     * @return MiddlewareException
     */
    public static function fromThrowable(Throwable $exception): MiddlewareException
    {
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }
}
