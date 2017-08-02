<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller;

use Exception;
use ExtendsFramework\Container\ContainerException;

class ControllerException extends Exception
{
    /**
     * Returns a new instance when no method found for $action.
     *
     * @param string $action
     * @return ControllerException
     */
    public static function forMethodNotFound(string $action): ControllerException
    {
        return new static(sprintf(
            'No controller action method can be found for action "%s".',
            $action
        ));
    }

    /**
     * Returns a new instance when no action is found for request.
     *
     * @param ContainerException $exception
     * @return ControllerException
     */
    public static function forActionNotFound(ContainerException $exception): ControllerException
    {
        return new static('No controller action was found in request.', 0, $exception);
    }
}
