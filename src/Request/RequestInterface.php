<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;

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
     * Add header with $name for $value.
     *
     * If header with $name already exists, it will be added to the array.
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
     * Get matched route.
     *
     * @return RouteInterface|null
     */
    public function getRoute(): ?RouteInterface;

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

    /**
     * Return new instance with $route.
     *
     * @param RouteInterface $route
     * @return RequestInterface
     */
    public function withRoute(RouteInterface $route): RequestInterface;
}
