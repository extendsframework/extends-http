<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

use ExtendsFramework\Container\ContainerInterface;

interface ResponseInterface
{
    /**
     * Return body.
     *
     * @return ContainerInterface
     */
    public function getBody(): ContainerInterface;

    /**
     * Return headers.
     *
     * @return ContainerInterface
     */
    public function getHeaders(): ContainerInterface;

    /**
     * Return status code.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Return new instance with header $value for $name.
     *
     * @param string $name
     * @param string $value
     * @return ResponseInterface
     */
    public function withHeader(string $name, string $value): ResponseInterface;
}
