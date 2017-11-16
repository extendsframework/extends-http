<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Path;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class PathRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * Constraints for matching the URI variables.
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
     * Path to match.
     *
     * @var string
     */
    protected $path;

    /**
     * Strict mode.
     *
     * @var bool
     */
    protected $strict = true;

    /**
     * Create new path route.
     *
     * Value of $path must be a part of the, or the whole, request URI path to match. Variables can be used and must
     * start with a semicolon followed by a name. The name must start with a letter and can only consist of
     * alphanumeric characters. When this condition is not matched, the variable will be skipped.
     *
     * The variable name will be checked for the constraint given in the $constraints array. When the variable name is
     * not found as array key, the default constraint \w+ will be used.
     *
     * For example: /foo/:bar/:baz/qux
     *
     * @param string $path
     * @param array  $constraints
     * @param array  $parameters
     */
    public function __construct(string $path, array $constraints = null, array $parameters = null)
    {
        $this->path = $path;
        $this->constraints = $constraints ?? [];
        $this->parameters = $parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if ((bool)preg_match($this->getPattern(), $request->getUri()->getPath(), $matches, PREG_OFFSET_CAPTURE, $pathOffset) === true) {
            return new RouteMatch($this->getParameters($matches), end($matches)[1]);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): RouteInterface
    {
        return new static($extra['path'], $extra['constraints'] ?? [], $extra['parameters'] ?? []);
    }

    /**
     * Set strict mode.
     *
     * If strict mode is on, the path must exactly match the request path. The path /foo/bar will not match the request
     * path /foo/bar/baz when strict mode is on. With strict mode off, /foo/bar will match anything after /foo/bar.
     *
     * @param bool|null $strict
     * @return PathRoute
     */
    public function setStrict(bool $strict = null): PathRoute
    {
        $this->strict = $strict ?? true;

        return $this;
    }

    /**
     * Get the parameters when the route is matches.
     *
     * The $matches will be filtered for integer keys and merged into the default parameters.
     *
     * @param array $matches
     * @return array
     */
    protected function getParameters(array $matches): array
    {
        $parameters = [];
        foreach ($matches as $key => $match) {
            if (is_string($key)) {
                $parameters[$key] = $match[0];
            }
        }

        return array_replace_recursive($this->parameters, $parameters);
    }

    /**
     * Get pattern to match request path.
     *
     * When strict mode is on, \z is added to the end of the pattern which means the string needs to be a exact match.
     *
     * @return string
     */
    protected function getPattern(): string
    {
        $path = preg_replace_callback('~:([a-z][a-z0-9\_]+)~i', function ($match) {
            return sprintf('(?<%s>%s)', $match[1], $this->constraints[$match[1]] ?? '\w+');
        }, $this->path);

        return sprintf(
            '~\G%s%s~',
            $path,
            $this->strict === true ? '(\z)' : '(/|\z)'
        );
    }
}
