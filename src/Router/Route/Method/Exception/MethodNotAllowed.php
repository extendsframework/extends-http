<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method\Exception;

use ExtendsFramework\Http\Router\Route\RouteException;
use LogicException;

class MethodNotAllowed extends LogicException implements RouteException
{
    /**
     * Allowed HTTP methods.
     *
     * @var array
     */
    protected $allowedMethods;

    /**
     * MethodNotAllowed constructor.
     *
     * @param string $method
     * @param array  $allowedMethods
     */
    public function __construct(string $method, array $allowedMethods)
    {
        parent::__construct(sprintf(
            'Method "%s" is not allowed.',
            $method
        ));

        $this->allowedMethods = $allowedMethods;
    }

    /**
     * Get all allowed methods.
     *
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return array_unique($this->allowedMethods);
    }
}
