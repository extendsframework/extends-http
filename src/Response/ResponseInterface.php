<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

interface ResponseInterface
{
    /**
     * Add header with $name for $value.
     *
     * If header with $name already exists, it will be added to the array.
     *
     * @param string $name
     * @param string $value
     * @return ResponseInterface
     */
    public function andHeader(string $name, string $value): ResponseInterface;

    /**
     * Return body.
     *
     * @return array|null
     */
    public function getBody(): ?array;

    /**
     * Return headers.
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Return status code.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Return new instance with $body.
     *
     * @param array $body
     * @return ResponseInterface
     */
    public function withBody(array $body): ResponseInterface;

    /**
     * Set header with $name for $value.
     *
     * If header with $name already exists, it will be overwritten.
     *
     * @param string $name
     * @param string $value
     * @return ResponseInterface
     */
    public function withHeader(string $name, string $value): ResponseInterface;

    /**
     * Return new instance with $headers.
     *
     * @param array $headers
     * @return ResponseInterface
     */
    public function withHeaders(array $headers): ResponseInterface;

    /**
     * Return new instance with $statusCode.
     *
     * @param int $statusCode
     * @return ResponseInterface
     */
    public function withStatusCode(int $statusCode): ResponseInterface;
}
