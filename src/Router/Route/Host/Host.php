<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Host;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Host\Exception\InvalidOptions;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class Host implements RouteInterface
{
    /**
     * Host to match.
     *
     * @var string
     */
    protected $host;

    /**
     * Default parameters to return.
     *
     * @var ContainerInterface
     */
    protected $parameters;

    /**
     * Create a method route.
     *
     * @param string             $host
     * @param ContainerInterface $parameters
     */
    public function __construct(string $host, ContainerInterface $parameters = null)
    {
        $this->host = $host;
        $this->parameters = $parameters ?? new Container();
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (!isset($options['host'])) {
            throw InvalidOptions::forMissingHost();
        }

        return new static(
            $options['host'],
            new Container($options['parameters'] ?? [])
        );
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if ($request->getUri()->getHost() === $this->host) {
            return new RouteMatch($this->parameters, $pathOffset);
        }

        return null;
    }
}
