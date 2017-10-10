<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Host;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Host\Exception\MissingHost;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;

class HostRoute implements RouteInterface
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
     * @var array
     */
    protected $parameters;

    /**
     * Create a method route.
     *
     * @param string $host
     * @param array  $parameters
     */
    public function __construct(string $host, array $parameters = null)
    {
        $this->host = $host;
        $this->parameters = $parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (array_key_exists('host', $options) === false) {
            throw new MissingHost();
        }

        return new static($options['host'], $options['parameters'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if ($request->getUri()->getHost() === $this->host) {
            return new RouteMatch($this->parameters);
        }

        return null;
    }
}
