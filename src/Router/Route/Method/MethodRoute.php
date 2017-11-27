<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
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
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    /**
     * Methods to match.
     *
     * @var array
     */
    protected $methods;

    /**
     * Default parameters to return.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Create a method route.
     *
     * @param array $methods
     * @param array $parameters
     */
    public function __construct(array $methods, array $parameters = null)
    {
        $this->parameters = $parameters ?? [];

        foreach ($methods as $method) {
            $this->addMethod($method);
        }
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $method = $request->getMethod();
        if (in_array(strtoupper($method), $this->methods, true) === true) {
            return new RouteMatch($this->parameters, $pathOffset);
        }

        throw new MethodNotAllowed($method, $this->methods);
    }

    /**
     * @inheritDoc
     */
    public function assemble(RequestInterface $request, array $path, array $parameters): RequestInterface
    {
        return $request;
    }

    /**
     * Add $method to route.
     *
     * @param string $method
     * @return MethodRoute
     */
    public function addMethod(string $method): MethodRoute
    {
        $this->methods[] = strtoupper(trim($method));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): RouteInterface
    {
        return new static((array)$extra['method'], $extra['parameters'] ?? []);
    }
}
