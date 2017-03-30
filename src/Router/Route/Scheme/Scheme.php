<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Scheme;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\Route\Scheme\Exception\InvalidOptions;

class Scheme implements RouteInterface
{
    /**
     * Parameters to return when route is matched.
     *
     * @var ContainerInterface
     */
    protected $parameters;

    /**
     * Scheme to match.
     *
     * @var string
     */
    protected $scheme;

    /**
     * Create a new scheme route.
     *
     * @param string             $scheme
     * @param ContainerInterface $parameters
     */
    public function __construct(string $scheme, ContainerInterface $parameters = null)
    {
        $this->scheme = strtoupper(trim($scheme));
        $this->parameters = $parameters ?? new Container();
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (!isset($options['scheme'])) {
            throw InvalidOptions::forMissingScheme();
        }

        return new static(
            $options['scheme'],
            new Container($options['parameters'] ?? [])
        );
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if (strtoupper($request->getUri()->getScheme()) === $this->scheme) {
            return new RouteMatch($this->parameters, $pathOffset);
        }

        return null;
    }
}
