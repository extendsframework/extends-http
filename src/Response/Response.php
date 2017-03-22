<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;

class Response implements ResponseInterface
{
    /**
     * @var ContainerInterface
     */
    protected $body;

    /**
     * @var ContainerInterface
     */
    protected $headers;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @inheritDoc
     */
    public function andHeader(string $name, string $value): ResponseInterface
    {
        $response = clone $this;
        $response->headers = $this->getHeaders()->with($name, $value);

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): ContainerInterface
    {
        if ($this->body === null) {
            $this->body = new Container();
        }

        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): ContainerInterface
    {
        if ($this->headers === null) {
            $this->headers = new Container();
        }

        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        if ($this->statusCode === null) {
            $this->statusCode = 200;
        }

        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function withBody(ContainerInterface $body): ResponseInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(ContainerInterface $headers): ResponseInterface
    {
        $clone = clone $this;
        $clone->headers = $headers;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withStatusCode(int $statusCode): ResponseInterface
    {
        $clone = clone $this;
        $clone->statusCode = $statusCode;

        return $clone;
    }
}
