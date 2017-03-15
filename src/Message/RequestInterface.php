<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

use ExtendsFramework\Container\ContainerInterface;

interface RequestInterface
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
     * @return RequestInterface
     */
    public function withBody(ContainerInterface $body): RequestInterface;

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
     * @return RequestInterface
     */
    public function withHeaders(ContainerInterface $headers): RequestInterface;

    /**
     * Return method.
     *
     * @return string
     */
    public function getMethod(): ?string;

    /**
     * Return new instance with $method.
     *
     * @param string $method
     * @return RequestInterface
     */
    public function withMethod(string $method): RequestInterface;

    /**
     * Return URI parameters.
     *
     * @return ContainerInterface
     */
    public function getParameters(): ?ContainerInterface;

    /**
     * Return new instance with $parameters.
     *
     * @param ContainerInterface $parameters
     * @return RequestInterface
     */
    public function withParameters(ContainerInterface $parameters): RequestInterface;

    /**
     * Return URI path.
     *
     * @return string
     */
    public function getPath(): ?string;

    /**
     * Return new instance with $path.
     *
     * @param string $path
     * @return RequestInterface
     */
    public function withPath(string $path): RequestInterface;
}
