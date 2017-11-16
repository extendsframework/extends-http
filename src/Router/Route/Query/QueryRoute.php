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

class QueryRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * Constraints for matching the query parameters.
     *
     * @var array
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

                if ((bool)preg_match($this->getPattern($constraint), $value, $matches) === false) {
                    throw new InvalidQueryString($path, $value, $constraint);
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
        return new static($extra['constraints'], $extra['parameters'] ?? []);
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

    /**
     * Get pattern to match query parameter.
     *
     * @param string $constraint
     * @return string
     */
    protected function getPattern(string $constraint): string
    {
        return sprintf('~^%s$~', $constraint);
    }
}
