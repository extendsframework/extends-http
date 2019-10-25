<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class Response implements ResponseInterface, StaticFactoryInterface
{
    /**
     * Response body.
     *
     * @var array
     */
    protected $body;

    /**
     * Response headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Response status code.
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @inheritDoc
     */
    public function andHeader(string $name, string $value): ResponseInterface
    {
        $clone = clone $this;
        if (array_key_exists($name, $clone->headers)) {
            if (!is_array($clone->headers[$name])) {
                $clone->headers[$name] = [
                    $clone->headers[$name],
                ];
            }

            $clone->headers[$name][] = $value;
        } else {
            $clone->headers[$name] = $value;
        }

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
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
    public function getHeader(string $name, $default = null)
    {
        return $this->headers[$name] ?? $default;
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
    public function withBody($body): ResponseInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, string $value): ResponseInterface
    {
        $response = clone $this;
        $response->headers[$name] = $value;

        return $response;
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
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new static();
    }
}
