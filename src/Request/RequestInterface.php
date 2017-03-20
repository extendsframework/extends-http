<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Container\ContainerInterface;

interface RequestInterface
{
    /**
     * Return custom attributes.
     *
     * @return ContainerInterface
     */
    public function getAttributes(): ContainerInterface;

    /**
     * Return body.
     *
     * @return ContainerInterface
     * @throws RequestException
     */
    public function getBody(): ContainerInterface;

    /**
     * Return headers.
     *
     * @return ContainerInterface
     */
    public function getHeaders(): ContainerInterface;

    /**
     * Return method.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Return request URI path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Return request URI query.
     *
     * @return ContainerInterface
     */
    public function getQuery(): ContainerInterface;

    /**
     * Return new instance with attribute $value for $name.
     *
     * @param string $name
     * @param mixed  $value
     * @return RequestInterface
     */
    public function withAttribute(string $name, $value): RequestInterface;
}
