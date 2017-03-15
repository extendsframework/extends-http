<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

use ExtendsFramework\Container\ContainerInterface;

class Request implements RequestInterface
{
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
     * URI parameters.
     *
     * @var ContainerInterface
     */
    protected $parameters;

    /**
     * URI path.
     *
     * @var string
     */
    protected $path;

    /**
     * @inheritDoc
     */
    public function getBody(): ?ContainerInterface
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function withBody(ContainerInterface $body): RequestInterface
    {
        $request = clone $this;
        $request->body = $body;

        return $request;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): ?ContainerInterface
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(ContainerInterface $headers): RequestInterface
    {
        $request = clone $this;
        $request->headers = $headers;

        return $request;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        $request = clone $this;
        $request->method = $method;

        return $request;
    }

    /**
     * @inheritDoc
     */
    public function getParameters(): ?ContainerInterface
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function withParameters(ContainerInterface $parameters): RequestInterface
    {
        $request = clone $this;
        $request->parameters = $parameters;

        return $request;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): RequestInterface
    {
        $request = clone $this;
        $request->path = $path;

        return $request;
    }
}
