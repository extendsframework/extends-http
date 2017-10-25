<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class Response implements ResponseInterface, StaticFactoryInterface
{
    /**
     * @var array
     */
    protected $body = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @inheritDoc
     */
    public function andHeader(string $name, string $value): ResponseInterface
    {
        $response = clone $this;
        $response->headers[$name] = $value;

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function withBody(array $body): ResponseInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(array $headers): ResponseInterface
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

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): ResponseInterface
    {
        return new static();
    }
}
