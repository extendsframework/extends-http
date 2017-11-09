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
     * @param mixed  $value
     * @return RequestInterface
     */
    public function andAttribute(string $name, $value): RequestInterface;

    /**
     * Add header with $name for $value.
     *
     * If header with $name already exists, it will be added to the array.
     *
     * @param string $name
     * @param mixed  $value
     * @return RequestInterface
     */
    public function andHeader(string $name, $value): RequestInterface;

    /**
     * Return custom attributes.
     *
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Get attribute for $key.
     *
     * Default value $default will be returned when attribute for $key does not exists.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getAttribute(string $key, $default = null);

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
     * Get header value for $name.
     *
     * Default value $default will be returned when header with $name does not exists.
     *
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function getHeader(string $name, $default = null);

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
     * Set header with $name for $value.
     *
     * If header with $name already exists, it will be overwritten.
     *
     * @param string $name
     * @param string $value
     * @return RequestInterface
     */
    public function withHeader(string $name, string $value): RequestInterface;

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
