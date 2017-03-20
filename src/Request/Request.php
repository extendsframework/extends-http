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
     * @param string             $method
     * @param string             $path
     * @param ContainerInterface $query
     * @param ContainerInterface $attributes
     * @param ContainerInterface $body
     * @param ContainerInterface $headers
     * @throws RequestException
     */
    public function __construct(string $method = null, string $path = null, ContainerInterface $query = null, ContainerInterface $body = null, ContainerInterface $headers = null, ContainerInterface $attributes = null)
    {
        $this->method = $method;
        $this->path = $path;
        $this->query = $query;
        $this->body = $body;
        $this->headers = $headers;
        $this->attributes = $attributes;
    }

    /**
     * @return ContainerInterface
     */
    public function getAttributes(): ContainerInterface
    {
        if ($this->attributes === null) {
            $this->attributes = new Container();
        }

        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): ContainerInterface
    {
        if ($this->body === null) {
            $body = json_decode(file_get_contents('php://input') ?: '[]', true);
            if ($body === null) {
                throw InvalidRequest::forInvalidBody(json_last_error_msg());
            }

            $this->body = new Container($body);
        }

        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): ContainerInterface
    {
        if ($this->headers === null) {
            foreach ($_SERVER as $name => $value) {
                if (strpos($name, 'HTTP_') === 0) {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }

            $this->headers = new Container($headers ?? []);
        }

        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        if ($this->method === null) {
            $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        }

        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if ($this->path === null) {
            $uri = explode('?', $_SERVER['REQUEST_URI'] ?? '/');
            $this->path = current($uri);
        }

        return $this->path;
    }

    /**
     * @return ContainerInterface
     */
    public function getQuery(): ContainerInterface
    {
        if ($this->query === null) {
            parse_str($_SERVER['QUERY_STRING'] ?? '', $query);

            $this->query = new Container($query);
        }

        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute(string $name, $value): RequestInterface
    {
        $request = clone $this;
        $request->attributes = $this->getAttributes()->with($name, $value);

        return $request;
    }
}
