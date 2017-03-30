<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;

interface RequestInterface
{
    /**
     * Merge $name and $value into existing attributes and return new instance.
     *
     * @param string $name
     * @param string $value
     * @return RequestInterface
     */
    public function andAttribute(string $name, string $value): RequestInterface;

    /**
     * Merge $name and $value into existing headers and return new instance.
     *
     * @param string $name
     * @param string $value
     * @return RequestInterface
     */
    public function andHeader(string $name, string $value): RequestInterface;

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
     * Return request URI.
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface;

    /**
     * Return new instance with $attributes.
     *
     * @param ContainerInterface $attributes
     * @return RequestInterface
     */
    public function withAttributes(ContainerInterface $attributes): RequestInterface;

    /**
     * Return new instance with $body.
     *
     * @param ContainerInterface $body
     * @return RequestInterface
     */
    public function withBody(ContainerInterface $body): RequestInterface;

    /**
     * Return new instance with $headers.
     *
     * @param ContainerInterface $headers
     * @return RequestInterface
     */
    public function withHeaders(ContainerInterface $headers): RequestInterface;

    /**
     * Return new instance with $method.
     *
     * @param string $method
     * @return RequestInterface
     */
    public function withMethod(string $method): RequestInterface;

    /**
     * Return new instance with $uri.
     *
     * @param UriInterface $uri
     * @return RequestInterface
     */
    public function withUri(UriInterface $uri): RequestInterface;
}
