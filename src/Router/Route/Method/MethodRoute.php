<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Method\Exception\MissingMethod;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class MethodRoute implements RouteInterface
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
     * @var array
     */
    protected $parameters;

    /**
     * Create a method route.
     *
     * @param string $method
     * @param array  $parameters
     */
    public function __construct(string $method, array $parameters = null)
    {
        $this->method = strtoupper(trim($method));
        $this->parameters = $parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (array_key_exists('method', $options) === false) {
            throw new MissingMethod();
        }

        return new static($options['method'], $options['parameters'] ?? []);
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
