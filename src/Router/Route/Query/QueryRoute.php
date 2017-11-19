<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Query\Exception\InvalidQueryString;
use ExtendsFramework\Http\Router\Route\Query\Exception\QueryParameterMissing;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Constraint\ConstraintInterface;
use ExtendsFramework\Validator\Constraint\ConstraintViolationInterface;

class QueryRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * Constraints for matching the query parameters.
     *
     * @var ConstraintInterface[]
     */
    protected $constraints;

    /**
     * Default parameters to return when route is matched.
     *
     * @var array
     */
    protected $parameters;

    /**
     * @param array $constraints
     * @param array $parameters
     */
    public function __construct(array $constraints, array $parameters = null)
    {
        $this->constraints = $constraints;
        $this->parameters = $parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $query = $request->getUri()->getQuery();

        $matched = [];
        foreach ($this->constraints as $path => $constraint) {
            if (array_key_exists($path, $query) === true) {
                $value = (string)$query[$path];

                $violation = $constraint->validate($value, $query);
                if ($violation instanceof ConstraintViolationInterface) {
                    throw new InvalidQueryString($path, $violation);
                }

                $matched[$path] = $value;
            } elseif (array_key_exists($path, $this->parameters) === false) {
                throw new QueryParameterMissing($path);
            }
        }

        return new RouteMatch($this->getParameters($matched), $pathOffset);
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): RouteInterface
    {
        $constraints = [];
        foreach ($extra['constraints'] ?? [] as $parameter => $constraint) {
            $constraints[$parameter] = $serviceLocator->getService($constraint['name'], $constraint['options'] ?? []);
        }

        return new static($constraints, $extra['parameters'] ?? []);
    }

    /**
     * Get the parameters when the route is matches.
     *
     * @param array $matches
     * @return array
     */
    protected function getParameters(array $matches): array
    {
        return array_replace_recursive($this->parameters, $matches);
    }
}
