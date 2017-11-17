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
    protected $body;

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
        $clone = clone $this;
        if (array_key_exists($name, $clone->headers) === true) {
            if (is_array($clone->headers[$name]) === false) {
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
    public function andBody(array $body): ResponseInterface
    {
        $clone = clone $this;
        $clone->body = array_merge(
            $clone->body ?? [],
            $body
        );

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): ?array
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
    public function withProblem(int $statusCode, string $type, string $title): ResponseInterface
    {
        return (clone $this)
            ->withStatusCode($statusCode)
            ->withBody([
                'type' => $type,
                'title' => $title,
            ]);
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): ResponseInterface
    {
        return new static();
    }
}
