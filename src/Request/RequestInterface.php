<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

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
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Return body.
     *
     * @return array
     * @throws RequestException
     */
    public function getBody(): array;

    /**
     * Return headers.
     *
     * @return array
     */
    public function getHeaders(): array;

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
     * @param array $attributes
     * @return RequestInterface
     */
    public function withAttributes(array $attributes): RequestInterface;

    /**
     * Return new instance with $body.
     *
     * @param array $body
     * @return RequestInterface
     */
    public function withBody(array $body): RequestInterface;

    /**
     * Return new instance with $headers.
     *
     * @param array $headers
     * @return RequestInterface
     */
    public function withHeaders(array $headers): RequestInterface;

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
