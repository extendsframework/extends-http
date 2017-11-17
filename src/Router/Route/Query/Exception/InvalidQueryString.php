<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query\Exception;

use ExtendsFramework\Http\Router\Route\Query\QueryRouteException;
use ExtendsFramework\Validator\Constraint\ConstraintViolationInterface;
use InvalidArgumentException;

class InvalidQueryString extends InvalidArgumentException implements QueryRouteException
{
    /**
     * Query string parameter.
     *
     * @var string
     */
    protected $parameter;

    /**
     * Query string constraint violation.
     *
     * @var ConstraintViolationInterface
     */
    protected $violation;

    /**
     * InvalidParameterValue constructor.
     *
     * @param string                       $parameter
     * @param ConstraintViolationInterface $violation
     */
    public function __construct(string $parameter, ConstraintViolationInterface $violation)
    {
        parent::__construct(sprintf(
            'Query string parameter "%s" failed due to violation "%s".',
            $parameter,
            (string)$violation
        ));

        $this->parameter = $parameter;
        $this->violation = $violation;
    }

    /**
     * Get query string parameter.
     *
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }

    /**
     * Get query string violations.
     *
     * @return ConstraintViolationInterface
     */
    public function getViolation(): ConstraintViolationInterface
    {
        return $this->violation;
    }
}
