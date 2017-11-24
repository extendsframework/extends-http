<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request\Uri;

class Uri implements UriInterface
{
    /**
     * Scheme of the URI.
     *
     * @var string
     */
    protected $scheme;

    /**
     * User of the URI.
     *
     * @var string
     */
    protected $user;

    /**
     * Password of the URI.
     *
     * @var string
     */
    protected $pass;

    /**
     * Host of the URI.
     *
     * @var string
     */
    protected $host;

    /**
     * Port of the URI.
     *
     * @var int
     */
    protected $port;

    /**
     * Path of the URI.
     *
     * @var string
     */
    protected $path;

    /**
     * Query or the URI.
     *
     * @var array
     */
    protected $query;

    /**
     * Fragment of the URI.
     *
     * @var array
     */
    protected $fragment = [];

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $uri = $this->getScheme() . '://' . $this->getAuthority() . $this->getPath();

        $query = $this->getQuery();
        if (empty($query) === false) {
            $uri .= '?' . http_build_query($query);
        }

        $fragment = $this->getFragment();
        if (empty($fragment) === false) {
            $uri .= '#' . http_build_query($fragment);
        }

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function andFragment(string $name, string $value): UriInterface
    {
        $clone = clone $this;
        $clone->fragment[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function andPath(string $path): UriInterface
    {
        $clone = clone $this;
        $clone->path = rtrim($clone->path ?? '', '/') . '/' . ltrim($path, '/');

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function andQuery(string $name, string $value): UriInterface
    {
        $clone = clone $this;
        $clone->query[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        $authority = $this->getHost();

        $userInfo = $this->getUserInfo();
        if ($userInfo !== null) {
            $authority = $userInfo . '@' . $authority;
        }

        $port = $this->getPort();
        if ($port !== null) {
            $authority .= ':' . $this->getPort();
        }

        return $authority;
    }

    /**
     * @inheritDoc
     */
    public function getFragment(): array
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        if ($this->host === null) {
            $this->host = $_SERVER['HTTP_HOST'];
        }

        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPass(): ?string
    {
        if ($this->pass === null) {
            $this->pass = $_SERVER['PHP_AUTH_PW'] ?? null;
        }

        return $this->pass;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        if ($this->path === null) {
            $this->path = strtok($_SERVER['REQUEST_URI'], '?');
        }

        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        if ($this->port === null) {
            $this->port = $_SERVER['SERVER_PORT'];
        }

        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): array
    {
        if ($this->query === null) {
            parse_str($_SERVER['QUERY_STRING'], $this->query);
        }

        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        if ($this->scheme === null) {
            $this->scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        }

        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getUser(): ?string
    {
        if ($this->user === null) {
            $this->user = $_SERVER['PHP_AUTH_USER'] ?? null;
        }

        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo(): ?string
    {
        $userInfo = null;
        if ($this->getUser()) {
            $userInfo = $this->getUser();

            if ($this->getPass()) {
                $userInfo .= ':' . $this->getPass();
            }
        }

        return $userInfo;
    }

    /**
     * @inheritDoc
     */
    public function withAuthority(string $host, string $user = null, string $pass = null, int $port = null): UriInterface
    {
        $uri = clone $this;
        $uri->host = $host;
        $uri->user = $user;
        $uri->pass = $pass;
        $uri->port = $port;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withFragment(array $fragment): UriInterface
    {
        $uri = clone $this;
        $uri->fragment = $fragment;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withHost(string $host): UriInterface
    {
        $uri = clone $this;
        $uri->host = $host;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withPass(string $pass): UriInterface
    {
        $uri = clone $this;
        $uri->pass = $pass;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): UriInterface
    {
        $uri = clone $this;
        $uri->path = $path;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withPort(int $port): UriInterface
    {
        $uri = clone $this;
        $uri->port = $port;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withQuery(array $query): UriInterface
    {
        $uri = clone $this;
        $uri->query = $query;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withScheme(string $scheme): UriInterface
    {
        $uri = clone $this;
        $uri->scheme = $scheme;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withUser(string $user): UriInterface
    {
        $uri = clone $this;
        $uri->user = $user;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo(string $user, string $pass): UriInterface
    {
        $uri = clone $this;
        $uri->user = $user;
        $uri->pass = $pass;

        return $uri;
    }
}
