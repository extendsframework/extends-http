<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

use ExtendsFramework\Container\Container;
use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Message\Exception\InvalidRequest;

class RequestFactory
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @param resource $resource
     */
    public function __construct($resource = null)
    {
        $this->resource = $resource ?: \fopen('php://input', 'rb');
    }

    /**
     * Create a new Request from globals.
     *
     * @return RequestInterface
     * @throws RequestException
     */
    public function create(): RequestInterface
    {
        return (new Request())
            ->withBody($this->getBody())
            ->withHeaders($this->getHeaders())
            ->withMethod($this->getMethod())
            ->withParameters($this->getParameters())
            ->withPath($this->getPath());
    }

    /**
     * Get parsed request body.
     *
     * The method json_decode() is used to parse the raw body.
     *
     * @return ContainerInterface
     * @throws RequestException
     */
    protected function getBody(): ContainerInterface
    {
        $rawBody = $this->getRawBody();
        $body = \json_decode($rawBody, true);
        if ($body === null) {
            throw InvalidRequest::forInvalidBody(\json_last_error_msg());
        }

        return new Container($body);
    }

    /**
     * Get raw request body.
     *
     * @return string
     */
    protected function getRawBody(): string
    {
        \rewind($this->resource);
        $rawBody = \stream_get_contents($this->resource);
        \fclose($this->resource);

        return $rawBody;
    }

    /**
     * Get request headers.
     *
     * A simple foreach is used in favor of the getallheaders() method. Latter is not always available.
     *
     * @return ContainerInterface
     */
    protected function getHeaders(): ContainerInterface
    {
        foreach ($_SERVER as $name => $value) {
            if (\strpos($name, 'HTTP_') === 0) {
                $headers[\str_replace(' ', '-', \ucwords(\strtolower(\str_replace('_', ' ', \substr($name, 5)))))] = $value;
            }
        }

        return new Container($headers ?? []);
    }

    /**
     * Get request method.
     *
     * @return string
     */
    protected function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Get query string parameters.
     *
     * @return ContainerInterface
     */
    protected function getParameters(): ContainerInterface
    {
        \parse_str($_SERVER['QUERY_STRING'] ?? '', $parameters);

        return new Container($parameters);
    }

    /**
     * Get request URI path.
     *
     * @return string
     */
    protected function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        return \strtok($path, '?');
    }
}
