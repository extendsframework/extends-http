<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatch;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class MethodRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_GET = 'GET';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

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
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $method = $request->getMethod();
        if (strtoupper($method) === $this->method) {
            return new RouteMatch($this->parameters);
        }

        throw new MethodNotAllowed($method, [$this->method]);
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): RouteInterface
    {
        return new static($extra['method'], $extra['parameters'] ?? []);
    }
}
