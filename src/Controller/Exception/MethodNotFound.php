<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller\Exception;

use Exception;
use ExtendsFramework\Http\Controller\ControllerException;

class MethodNotFound extends Exception implements ControllerException
{
    /**
     * When method for $action is missing.
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct(sprintf(
            'No controller method can be found for action "%s".',
            $action
        ));
    }
}
