<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Controller\Exception;

use Exception;
use ExtendsFramework\Http\Controller\ControllerException;

class ActionNotFound extends Exception implements ControllerException
{
    /**
     * When action is missing in request.
     */
    public function __construct()
    {
        parent::__construct('No controller action was found in request.');
    }
}
