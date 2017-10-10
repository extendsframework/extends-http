<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Query\Exception\MissingConstraints;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class QueryRoute implements RouteInterface
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
    public static function factory(array $options): RouteInterface
    {
        if (array_key_exists('constraints', $options) === false) {
            throw new MissingConstraints();
        }

        return new static($options['constraints'], $options['parameters'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $query = $request->getUri()->getQuery();

        $matched = [];
        foreach ($this->constraints as $path => $constraint) {
            if (array_key_exists($path, $query)) {
                if ((bool)preg_match($this->getPattern($constraint), $query[$path], $matches) === true) {
                    $matched[$path] = current($matches);
                } else {
                    return null;
                }
            }
        }

        return new RouteMatch($this->getParameters($matched));
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
