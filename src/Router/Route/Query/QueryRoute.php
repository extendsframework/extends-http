<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Query\Exception\InvalidOptions;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class QueryRoute implements RouteInterface
{
    /**
     * Constraints for matching the query parameters.
     *
     * @var ContainerInterface
     */
    protected $constraints;

    /**
     * Default parameters to return when route is matched.
     *
     * @var ContainerInterface
     */
    protected $parameters;

    /**
     * @param ContainerInterface $constraints
     * @param ContainerInterface $parameters
     */
    public function __construct(ContainerInterface $constraints, ContainerInterface $parameters = null)
    {
        $this->constraints = $constraints;
        $this->parameters = $parameters ?? new Container();
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (!isset($options['constraints'])) {
            throw InvalidOptions::forMissingConstraints();
        }

        return new static(
            new Container($options['constraints']),
            new Container($options['parameters'] ?? [])
        );
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $query = $request->getUri()->getQuery();

        foreach ($this->constraints as $path => $constraint) {
            if ($query->has($path)) {
                if (preg_match($this->getPattern($constraint), $query->get($path), $matches)) {
                    $matched[$path] = current($matches);
                } else {
                    return null;
                }
            }
        }

        return new RouteMatch($this->getParameters($matched ?? []));
    }

    /**
     * Get the parameters when the route is matches.
     *
     * @param array $matches
     * @return ContainerInterface
     */
    protected function getParameters(array $matches): ContainerInterface
    {
        return $this->parameters->merge(new Container($matches));
    }

    /**
     * Get pattern to match query parameter.
     *
     * @param string $constraint
     * @return string
     */
    protected function getPattern(string $constraint): string
    {
        return sprintf(
            '~^%s$~',
            $constraint
        );
    }
}
