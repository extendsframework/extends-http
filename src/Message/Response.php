<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

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
    public function getBody(): ?ContainerInterface
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function withBody(ContainerInterface $body): ResponseInterface
    {
        $response = clone $this;
        $response->body = $body;

        return $response;
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
    public function withHeaders(ContainerInterface $headers): ResponseInterface
    {
        $response = clone $this;
        $response->headers = $headers;

        return $response;
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
    public function withStatusCode(int $statusCode): ResponseInterface
    {
        $response = clone $this;
        $response->statusCode = $statusCode;

        return $response;
    }
}
