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
    public function __construct(ContainerInterface $body = null, ContainerInterface $headers = null, int $statusCode = null)
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
    public function withHeader(string $name, string $value): ResponseInterface
    {
        $response = clone $this;
        $response->headers = $this->getHeaders()->with($name, $value);

        return $response;
    }
}
