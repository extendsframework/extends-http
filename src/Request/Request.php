<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Http\Request\Exception\InvalidRequestBody;
use ExtendsFramework\Http\Request\Uri\Uri;
use ExtendsFramework\Http\Request\Uri\UriInterface;

class Request implements RequestInterface
{
    /**
     * Custom request attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Post data.
     *
     * @var array
     */
    protected $body;

    /**
     * Request headers.
     *
     * @var array
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
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function andHeader(string $name, string $value): RequestInterface
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;

        return $clone;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): array
    {
        if ($this->body === null) {
            $this->body = json_decode(file_get_contents('php://input') ?: '[]', true);
            if ($this->body === null) {
                throw new InvalidRequestBody(json_last_error_msg());
            }
        }

        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        if ($this->headers === null) {
            foreach ($_SERVER as $name => $value) {
                if (strpos($name, 'HTTP_') === 0) {
                    $name = substr($name, 5);
                    $name = str_replace('_', ' ', $name);
                    $name = strtolower($name);
                    $name = ucwords($name);
                    $name = str_replace(' ', '-', $name);

                    $this->headers[$name] = $value;
                }
            }
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
    public function withAttributes(array $attributes): RequestInterface
    {
        $clone = clone $this;
        $clone->attributes = $attributes;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withBody(array $body): RequestInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(array $headers): RequestInterface
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
