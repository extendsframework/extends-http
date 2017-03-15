<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

use ExtendsFramework\Container\ContainerInterface;

interface ResponseInterface
{
    /**
     * Return body.
     *
     * @return ContainerInterface
     */
    public function getBody(): ?ContainerInterface;

    /**
     * Return new instance with $body.
     *
     * @param ContainerInterface $body
     * @return ResponseInterface
     */
    public function withBody(ContainerInterface $body): ResponseInterface;

    /**
     * Return headers.
     *
     * @return ContainerInterface
     */
    public function getHeaders(): ?ContainerInterface;

    /**
     * Return new instance with $headers.
     *
     * @param ContainerInterface $headers
     * @return ResponseInterface
     */
    public function withHeaders(ContainerInterface $headers): ResponseInterface;

    /**
     * Return status code.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Return new instance with $statusCode.
     *
     * @param int $statusCode
     * @return ResponseInterface
     */
    public function withStatusCode(int $statusCode): ResponseInterface;
}
