<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request\Uri;

interface UriInterface
{
    /**
     * Merge $name and $value into existing fragment and return new instance.
     *
     * @param string $name
     * @param string $value
     * @return UriInterface
     */
    public function andFragment(string $name, string $value): UriInterface;

    /**
     * Merge $name and $value into existing query and return new instance.
     *
     * @param string $name
     * @param string $value
     * @return UriInterface
     */
    public function andQuery(string $name, string $value): UriInterface;

    /**
     * Get authority from URI.
     *
     * @return string
     */
    public function getAuthority(): string;

    /**
     * Get fragment from URI.
     *
     * @return array
     */
    public function getFragment(): array;

    /**
     * Get host from URI.
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Get password from URI.
     *
     * @return string
     */
    public function getPass(): ?string;

    /**
     * Get path from URI.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Get port from URI.
     *
     * @return int
     */
    public function getPort(): ?int;

    /**
     * Get query from URI.
     *
     * @return array
     */
    public function getQuery(): array;

    /**
     * Get scheme from URI.
     *
     * @return string
     */
    public function getScheme(): string;

    /**
     * Get user from URI.
     *
     * @return string
     */
    public function getUser(): ?string;

    /**
     * Get scheme from URI.
     *
     * @return string
     */
    public function getUserInfo(): ?string;

    /**
     * Return new instance with authority.
     *
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param int    $port
     * @return UriInterface
     */
    public function withAuthority(
        string $host,
        string $user = null,
        string $pass = null,
        int $port = null
    ): UriInterface;

    /**
     * Return new instance with $fragment.
     *
     * @param array $fragment
     * @return UriInterface
     */
    public function withFragment(array $fragment): UriInterface;

    /**
     * Return new instance with $host.
     *
     * @param string $host
     * @return UriInterface
     */
    public function withHost(string $host): UriInterface;

    /**
     * Return new instance with $pass.
     *
     * @param string $pass
     * @return UriInterface
     */
    public function withPass(string $pass): UriInterface;

    /**
     * Return new instance with $path.
     *
     * @param string $path
     * @return UriInterface
     */
    public function withPath(string $path): UriInterface;

    /**
     * Return new instance with $port.
     *
     * @param int $port
     * @return UriInterface
     */
    public function withPort(int $port): UriInterface;

    /**
     * Return new instance with $query.
     *
     * @param array $query
     * @return UriInterface
     */
    public function withQuery(array $query): UriInterface;

    /**
     * Return new instance with $scheme.
     *
     * @param string $scheme
     * @return UriInterface
     */
    public function withScheme(string $scheme): UriInterface;

    /**
     * Return new instance with $user.
     *
     * @param string $user
     * @return UriInterface
     */
    public function withUser(string $user): UriInterface;

    /**
     * Return new instance with $user and $pass user info.
     *
     * @param string $user
     * @param string $pass
     * @return UriInterface
     */
    public function withUserInfo(string $user, string $pass): UriInterface;

    /**
     * Get relative URI.
     *
     * @return string
     */
    public function toRelative(): string;

    /**
     * Get absolute URI.
     *
     * @return string
     */
    public function toAbsolute(): string;
}
