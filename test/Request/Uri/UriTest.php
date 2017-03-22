<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request\Uri;

use ExtendsFramework\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::__toString()
     */
    public function testCanCreateString(): void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'www.extends.nl';
        $_SERVER['PHP_AUTH_PW'] = 'framework';
        $_SERVER['PHP_AUTH_USER'] = 'extends';
        $_SERVER['REQUEST_URI'] = '/foo/bar?baz=qux+quux';
        $_SERVER['QUERY_STRING'] = 'baz=qux+quux';
        $_SERVER['SERVER_PORT'] = 443;

        $fragment = $this->createMock(ContainerInterface::class);
        $fragment
            ->expects($this->once())
            ->method('extract')
            ->willReturn([
                'bar' => 'baz',
            ]);

        /**
         * @var ContainerInterface $fragment
         */
        $uri = (new Uri())->withFragment($fragment);

        $this->assertSame('https://extends:framework@www.extends.nl:443/foo/bar?baz=qux+quux#bar=baz', (string)$uri);
    }

    /**
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
    public function testCanGetFromEnvironmentVariables(): void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'www.extends.nl';
        $_SERVER['PHP_AUTH_PW'] = 'framework';
        $_SERVER['PHP_AUTH_USER'] = 'extends';
        $_SERVER['REQUEST_URI'] = '/foo/bar?baz=qux+quux';
        $_SERVER['QUERY_STRING'] = 'baz=qux+quux';
        $_SERVER['SERVER_PORT'] = 443;

        $uri = new Uri();

        $this->assertSame('extends:framework@www.extends.nl:443', $uri->getAuthority());
        $this->assertInstanceOf(ContainerInterface::class, $uri->getFragment());
        $this->assertSame('www.extends.nl', $uri->getHost());
        $this->assertSame('framework', $uri->getPass());
        $this->assertSame('/foo/bar', $uri->getPath());
        $this->assertSame(443, $uri->getPort());
        $this->assertSame('qux quux', $uri->getQuery()->get('baz'));
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('extends', $uri->getUser());
        $this->assertSame('extends:framework', $uri->getUserInfo());
    }

    /**
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
    public function testCanGetFromWithMethods(): void
    {
        $fragment = $this->createMock(ContainerInterface::class);

        $query = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $fragment
         * @var ContainerInterface $query
         */
        $uri = (new Uri())
            ->withFragment($fragment)
            ->withHost('www.extends.nl')
            ->withPass('framework')
            ->withPath('/foo/bar')
            ->withPort(443)
            ->withQuery($query)
            ->withScheme('https')
            ->withUser('extends');

        $this->assertSame($fragment, $uri->getFragment());
        $this->assertSame('www.extends.nl', $uri->getHost());
        $this->assertSame('framework', $uri->getPass());
        $this->assertSame('/foo/bar', $uri->getPath());
        $this->assertSame(443, $uri->getPort());
        $this->assertSame($query, $uri->getQuery());
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('extends', $uri->getUser());
    }

    /**
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withAuthority()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getHost()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPass()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getUser()
     */
    public function testCanGetWithAuthority(): void
    {
        $uri = (new Uri())->withAuthority('www.extends.nl', 'extends', 'framework', 80);

        $this->assertSame('www.extends.nl', $uri->getHost());
        $this->assertSame('framework', $uri->getPass());
        $this->assertSame(80, $uri->getPort());
        $this->assertSame('extends', $uri->getUser());
    }

    /**
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withUserInfo()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getPass()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getUser()
     */
    public function testCanGetWithUserInfo(): void
    {
        $uri = (new Uri())->withUserInfo('extends', 'framework');

        $this->assertSame('framework', $uri->getPass());
        $this->assertSame('extends', $uri->getUser());
    }

    /**
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withFragment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::andFragment()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getFragment()
     */
    public function testCanMergeWithFragment(): void
    {
        $fragment = $this->createMock(ContainerInterface::class);
        $fragment
            ->expects($this->once())
            ->method('with')
            ->with('foo', 'bar')
            ->willReturnSelf();

        /**
         * @var ContainerInterface $fragment
         */
        $uri = (new Uri())
            ->withFragment($fragment)
            ->andFragment('foo', 'bar');

        $this->assertSame($fragment, $uri->getFragment());
    }

    /**
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::withQuery()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::andQuery()
     * @covers \ExtendsFramework\Http\Request\Uri\Uri::getQuery()
     */
    public function testCanMergeWithQuery(): void
    {
        $query = $this->createMock(ContainerInterface::class);
        $query
            ->expects($this->once())
            ->method('with')
            ->with('foo', 'bar')
            ->willReturnSelf();

        /**
         * @var ContainerInterface $query
         */
        $uri = (new Uri())
            ->withQuery($query)
            ->andQuery('foo', 'bar');

        $this->assertSame($query, $uri->getQuery());
    }
}
