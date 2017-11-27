<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request\Uri;

use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return the correct $_SERVER values.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::fromEnvironment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getAuthority()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getFragment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getHost()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPass()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPath()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPort()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getQuery()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getScheme()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getUser()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getUserInfo()
     */
    public function testGetMethods(): void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'www.extends.nl';
        $_SERVER['PHP_AUTH_PW'] = 'framework';
        $_SERVER['PHP_AUTH_USER'] = 'extends';
        $_SERVER['REQUEST_URI'] = '/foo/bar?baz=qux+quux';
        $_SERVER['QUERY_STRING'] = 'baz=qux+quux';
        $_SERVER['SERVER_PORT'] = '443';

        $uri = Uri::fromEnvironment();

        $this->assertSame('extends:framework@www.extends.nl:443', $uri->getAuthority());
        $this->assertSame([], $uri->getFragment());
        $this->assertSame('www.extends.nl', $uri->getHost());
        $this->assertSame('framework', $uri->getPass());
        $this->assertSame('/foo/bar', $uri->getPath());
        $this->assertSame(443, $uri->getPort());
        $this->assertSame(['baz' => 'qux quux'], $uri->getQuery());
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('extends', $uri->getUser());
        $this->assertSame('extends:framework', $uri->getUserInfo());
    }

    /**
     * With methods.
     *
     * Test that with methods will set the correct value.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withFragment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withHost()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withPass()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withPath()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withPort()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withQuery()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withScheme()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withUser()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getFragment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getHost()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPass()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPath()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPort()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getQuery()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getScheme()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getUser()
     */
    public function testWithMethods(): void
    {
        $uri = (new Uri())
            ->withFragment(['foo' => 'bar'])
            ->withHost('www.extends.nl')
            ->withPass('framework')
            ->withPath('/foo/bar')
            ->withPort(443)
            ->withQuery(['qux' => 'quux'])
            ->withScheme('https')
            ->withUser('extends');

        $this->assertSame(['foo' => 'bar'], $uri->getFragment());
        $this->assertSame('www.extends.nl', $uri->getHost());
        $this->assertSame('framework', $uri->getPass());
        $this->assertSame('/foo/bar', $uri->getPath());
        $this->assertSame(443, $uri->getPort());
        $this->assertSame(['qux' => 'quux'], $uri->getQuery());
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('extends', $uri->getUser());
    }

    /**
     * With Authority.
     *
     * Test that whole authority will be set.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withAuthority()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getHost()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPass()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getUser()
     */
    public function testWithAuthority(): void
    {
        $uri = (new Uri())->withAuthority('www.extends.nl', 'extends', 'framework', 80);

        $this->assertSame('www.extends.nl', $uri->getHost());
        $this->assertSame('framework', $uri->getPass());
        $this->assertSame(80, $uri->getPort());
        $this->assertSame('extends', $uri->getUser());
    }

    /**
     * With user info.
     *
     * Test that whole user info will be set.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withUserInfo()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPass()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getUser()
     */
    public function testWithUserInfo(): void
    {
        $uri = (new Uri())->withUserInfo('extends', 'framework');

        $this->assertSame('framework', $uri->getPass());
        $this->assertSame('extends', $uri->getUser());
    }

    /**
     * And fragment.
     *
     * Test that a single fragment parameter can be set.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withFragment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::andFragment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getFragment()
     */
    public function testAndFragment(): void
    {
        $uri = (new Uri())
            ->andFragment('foo', 'bar')
            ->andFragment('qux', 'quux');

        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $uri->getFragment());
    }

    /**
     * And query.
     *
     * Test that a single query parameter can be set.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withQuery()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::andQuery()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getQuery()
     */
    public function testAndQuery(): void
    {
        $uri = (new Uri())
            ->andQuery('foo', 'bar')
            ->andQuery('qux', 'quux');

        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $uri->getQuery());
    }

    /**
     * To relative.
     *
     * Test that method will return relative URI.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::fromEnvironment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::toRelative()
     */
    public function testToRelative(): void
    {
        $_SERVER['REQUEST_URI'] = '/foo/bar?baz=qux+quux';
        $_SERVER['QUERY_STRING'] = 'baz=qux+quux';

        $uri = Uri::fromEnvironment()->withFragment([
            'bar' => 'baz',
        ]);

        $this->assertSame('/foo/bar?baz=qux+quux#bar=baz', $uri->toRelative());
    }

    /**
     * To absolute.
     *
     * Test that method will return absolute URI.
     *
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::fromEnvironment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::toAbsolute()
     */
    public function testToAbsolute(): void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'www.extends.nl';
        $_SERVER['PHP_AUTH_PW'] = 'framework';
        $_SERVER['PHP_AUTH_USER'] = 'extends';
        $_SERVER['REQUEST_URI'] = '/foo/bar?baz=qux+quux';
        $_SERVER['QUERY_STRING'] = 'baz=qux+quux';
        $_SERVER['SERVER_PORT'] = '443';

        $uri = Uri::fromEnvironment()->withFragment([
            'bar' => 'baz',
        ]);

        $this->assertSame('https://extends:framework@www.extends.nl:443/foo/bar?baz=qux+quux#bar=baz', $uri->toAbsolute());
    }
}
