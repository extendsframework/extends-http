<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Method\Exception\InvalidOptions;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class Method implements RouteInterface
{
    /**
     * Method to match.
     *
     * @var string
     */
    protected $method;

    /**
     * Default parameters to return.
     *
     * @var ContainerInterface
     */
    protected $parameters;

    /**
     * Create a method route.
     *
     * @param string             $method
     * @param ContainerInterface $parameters
     */
    public function __construct(string $method, ContainerInterface $parameters = null)
    {
        $this->method = strtoupper(trim($method));
        $this->parameters = $parameters ?? new Container();
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (!isset($options['method'])) {
            throw InvalidOptions::forMissingMethod();
        }

        return new static(
            $options['method'],
            new Container($options['parameters'] ?? [])
        );
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if (strtoupper($request->getMethod()) === $this->method) {
            return new RouteMatch($this->parameters);
        }

        return null;
    }
}
