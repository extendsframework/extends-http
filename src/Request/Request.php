<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\Exception\InvalidRequest;

class Request implements RequestInterface
{
    /**
     * Custom request attributes.
     *
     * @var ContainerInterface
     */
    protected $attributes;

    /**
     * Post data.
     *
     * @var ContainerInterface
     */
    protected $body;

    /**
     * Request headers.
     *
     * @var ContainerInterface
     */
    protected $headers;

    /**
     * Request method.
     *
     * @var string
     */
    protected $method;

    /**
     * Request URI.
     *
     * @var string
     */
    protected $path;

    /**
     * Request query parameters.
     *
     * @var ContainerInterface
     */
    protected $query;

    /**
     * @param ContainerInterface $attributes
     * @param ContainerInterface $body
     * @param ContainerInterface $headers
     * @param string             $method
     * @param string             $path
     * @param ContainerInterface $query
     * @throws RequestException
     */
    public function __construct(ContainerInterface $attributes, ContainerInterface $body, ContainerInterface $headers, string $method, string $path, ContainerInterface $query)
    {
        $this->attributes = $attributes;
        $this->body = $body;
        $this->headers = $headers;
        $this->method = $method;
        $this->path = $path;
        $this->query = $query;
    }

    /**
     * @return ContainerInterface
     */
    public function getAttributes(): ContainerInterface
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): ContainerInterface
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): ContainerInterface
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return ContainerInterface
     */
    public function getQuery(): ContainerInterface
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute(string $name, $value): RequestInterface
    {
        $request = clone $this;
        $request->attributes = $this->attributes->with($name, $value);

        return $request;
    }

    /**
     * @return RequestInterface
     * @throws RequestException
     */
    public static function fromEnvironment(): RequestInterface
    {
        $contents = file_get_contents('php://input');
        if ($contents !== '') {
            $body = json_decode($contents, true);
            if ($body === null) {
                throw InvalidRequest::forInvalidBody(json_last_error_msg());
            }
        }

        foreach ($_SERVER as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        $uri = parse_url($_SERVER['REQUEST_URI']);
        parse_str($uri['query'] ?? '', $query);

        return new static(
            new Container(),
            new Container($body ?? []),
            new Container($headers ?? []),
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $uri['path'],
            new Container($query)
        );
    }
}
