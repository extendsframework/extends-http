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
     * @param ContainerInterface $body
     * @param ContainerInterface $headers
     * @param int                $statusCode
     */
    public function __construct(ContainerInterface $body, ContainerInterface $headers, int $statusCode)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
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
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, string $value): ResponseInterface
    {
        $response = clone $this;
        $response->headers = $this->headers->with($name, $value);

        return $response;
    }

    /**
     * @param array $body
     * @param array $headers
     * @param int   $statusCode
     * @return static
     */
    public static function forResult(array $body = null, array $headers = null, int $statusCode = null)
    {
        return new static(
            new Container($body ?: []),
            new Container($headers ?: []),
            $statusCode ?: 200
        );
    }
}
