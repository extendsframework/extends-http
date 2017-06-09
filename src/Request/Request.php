<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\Exception\InvalidRequest;
use ExtendsFramework\Http\Request\Uri\Uri;
use ExtendsFramework\Http\Request\Uri\UriInterface;

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
     * @var UriInterface
     */
    protected $uri;

    /**
     * @inheritDoc
     */
    public function andAttribute(string $name, string $value): RequestInterface
    {
        $clone = clone $this;
        $clone->attributes = $clone->getAttributes()->with($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function andHeader(string $name, string $value): RequestInterface
    {
        $clone = clone $this;
        $clone->headers = $clone->getHeaders()->with($name, $value);

        return $clone;
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
        $headers = [];
        if ($this->headers === null) {
            foreach ($_SERVER as $name => $value) {
                if (strpos($name, 'HTTP_') === 0) {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }

            $this->headers = new Container($headers);
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
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        if ($this->uri === null) {
            $this->uri = new Uri();
        }

        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withAttributes(ContainerInterface $attributes): RequestInterface
    {
        $clone = clone $this;
        $clone->attributes = $attributes;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withBody(ContainerInterface $body): RequestInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(ContainerInterface $headers): RequestInterface
    {
        $clone = clone $this;
        $clone->headers = $headers;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }
}
