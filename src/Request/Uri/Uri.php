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
    protected $host = 'GET';

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
    protected $path = '/';

    /**
     * Query or the URI.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Fragment of the URI.
     *
     * @var array
     */
    protected $fragment = [];

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
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPass(): ?string
    {
        return $this->pass;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getUser(): ?string
    {
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

    /**
     * @inheritDoc
     */
    public function toRelative(): string
    {
        $uri = $this->getPath();

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
    public function toAbsolute(): string
    {
        return $this->getScheme() . '://' . $this->getAuthority() . $this->toRelative();
    }

    /**
     * Create URI from environment variables.
     *
     * @return UriInterface
     */
    public static function fromEnvironment(): UriInterface
    {
        parse_str($_SERVER['QUERY_STRING'], $query);

        $uri = (new static())
            ->withScheme((isset($_SERVER['HTTPS']) === true && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
            ->withHost($_SERVER['HTTP_HOST'])
            ->withPort((int)$_SERVER['SERVER_PORT'])
            ->withPath(strtok($_SERVER['REQUEST_URI'], '?'))
            ->withQuery($query);

        if (array_key_exists('PHP_AUTH_USER', $_SERVER) === true) {
            $uri = $uri->withUser($_SERVER['PHP_AUTH_USER']);
        }

        if (array_key_exists('PHP_AUTH_PW', $_SERVER) === true) {
            $uri = $uri->withPass($_SERVER['PHP_AUTH_PW']);
        }

        return $uri;
    }
}
