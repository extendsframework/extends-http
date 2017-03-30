<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller\Exception;

use ExtendsFramework\Http\Controller\ControllerException;

class MethodNotFound extends ControllerException
{
    /**
     * Returns a new instance when no method found for $action.
     *
     * @param string $action
     * @return ControllerException
     */
    public static function forAction(string $action): ControllerException
    {
        return new static(\sprintf(
            'No controller action method can be found for action "%s".',
            $action
        ));
    }
}
