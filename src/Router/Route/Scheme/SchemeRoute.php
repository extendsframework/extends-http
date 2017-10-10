<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Scheme;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Http\Router\Route\Scheme\Exception\MissingScheme;

class SchemeRoute implements RouteInterface
{
    /**
     * Parameters to return when route is matched.
     *
     * @var array
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
     * @param string $scheme
     * @param array  $parameters
     */
    public function __construct(string $scheme, array $parameters = null)
    {
        $this->scheme = strtoupper(trim($scheme));
        $this->parameters = $parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public static function factory(array $options): RouteInterface
    {
        if (array_key_exists('scheme', $options) === false) {
            throw new MissingScheme();
        }

        return new static($options['scheme'], $options['parameters'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if (strtoupper($request->getUri()->getScheme()) === $this->scheme) {
            return new RouteMatch($this->parameters);
        }

        return null;
    }
}
