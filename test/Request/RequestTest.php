<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return the correct php://input abd $_SERVER values.
     *
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::getBody()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testCanCreateNewInstanceFromEnvironment(): void
    {
        Buffer::set('{"foo":"qux"}');

        $_SERVER['HTTP_HOST'] = 'www.example.com';
        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = 'baz=qux';
        $_SERVER['REQUEST_URI'] = '/foo/bar';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

        $request = Request::fromEnvironment();

        $this->assertSame([], $request->getAttributes());
        $this->assertEquals((object)['foo' => 'qux',], $request->getBody());
        $this->assertSame([
            'Host' => 'www.example.com',
            'Content-Type' => 'application/json',
        ], $request->getHeaders());
        $this->assertSame('POST', $request->getMethod());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());

        Buffer::reset();
    }

    /**
     * With methods.
     *
     * Test that with methods will set the correct value.
     *
     * @covers  \ExtendsFramework\Http\Request\Request::withAttributes()
     * @covers  \ExtendsFramework\Http\Request\Request::withBody()
     * @covers  \ExtendsFramework\Http\Request\Request::withHeaders()
     * @covers  \ExtendsFramework\Http\Request\Request::withMethod()
     * @covers  \ExtendsFramework\Http\Request\Request::withUri()
     * @covers  \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers  \ExtendsFramework\Http\Request\Request::getAttribute()
     * @covers  \ExtendsFramework\Http\Request\Request::getBody()
     * @covers  \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers  \ExtendsFramework\Http\Request\Request::getHeader()
     * @covers  \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers  \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testWithMethods(): void
    {
        $uri = $this->createMock(UriInterface::class);

        /**
         * @var UriInterface $uri
         */
        $request = (new Request())
            ->withAttributes(['foo' => 'bar'])
            ->withBody(['baz' => 'qux'])
            ->withHeaders(['qux' => 'quux'])
            ->withMethod('POST')
            ->withUri($uri);

        $this->assertSame(['foo' => 'bar'], $request->getAttributes());
        $this->assertSame(['baz' => 'qux'], $request->getBody());
        $this->assertSame(['qux' => 'quux'], $request->getHeaders());
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
        $this->assertSame('quux', $request->getHeader('qux'));
        $this->assertSame('bar', $request->getAttribute('foo'));

        // Default return values.
        $this->assertSame('qux', $request->getHeader('bar', 'qux'));
        $this->assertSame('quux', $request->getAttribute('bar', 'quux'));
    }

    /**
     * And attribute.
     *
     * Test that a single attribute parameter can be set.
     *
     * @covers \ExtendsFramework\Http\Request\Request::andAttribute()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     */
    public function testCanMergeWithAttribute(): void
    {
        $request = (new Request())
            ->andAttribute('foo', 'bar')
            ->andAttribute('qux', 'quux');

        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $request->getAttributes());
    }

    /**
     * And header.
     *
     * Test that a single header parameter can be set.
     *
     * @covers \ExtendsFramework\Http\Request\Request::andHeader()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     */
    public function testAndHeader(): void
    {
        $request = (new Request())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => [
                'bar',
                'baz',
            ],
            'qux' => 'quux',
        ], $request->getHeaders());
    }

    /**
     * With headers.
     *
     * Test that already set header will be overwritten.
     *
     * @covers \ExtendsFramework\Http\Request\Request::andHeader()
     * @covers \ExtendsFramework\Http\Request\Request::withHeader()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getHeader()
     */
    public function testWithHeader(): void
    {
        $request = (new Request())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->withHeader('foo', 'qux')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => 'qux',
            'qux' => 'quux',
        ], $request->getHeaders());
    }

    /**
     * Get URI.
     *
     * Test that default URI object will be returned.
     *
     * @covers \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testGetUri(): void
    {
        $uri = (new Request())->getUri();

        $this->assertInstanceOf(UriInterface::class, $uri);
    }

    /**
     * Invalid body.
     *
     * Test that invalid body can not be parsed and a exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Http\Request\Request::fromEnvironment()
     * @covers                   \ExtendsFramework\Http\Request\Exception\InvalidRequestBody::__construct()
     * @expectedException        \ExtendsFramework\Http\Request\Exception\InvalidRequestBody
     * @expectedExceptionMessage Invalid JSON for request body, got parse error "Syntax error".
     */
    public function testInvalidBody(): void
    {
        Buffer::set('{"foo":"qux"');

        Request::fromEnvironment();

        Buffer::reset();
    }

    /**
     * Empty body.
     *
     * Test that empty body is allowed for request.
     *
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     */
    public function testEmptyBody(): void
    {
        Buffer::set('');

        $request = Request::fromEnvironment();

        $this->assertNull($request->getBody());

        Buffer::reset();
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RequestInterface.
     *
     * @covers \ExtendsFramework\Http\Request\Request::factory()
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     */
    public function testFactory(): void
    {
        Buffer::set('{}');

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $request = Request::factory(RequestInterface::class, $serviceLocator);

        $this->assertInstanceOf(RequestInterface::class, $request);

        Buffer::reset();
    }
}

class Buffer
{
    protected static $value;

    public static function get(): string
    {
        return static::$value;
    }

    public static function set(string $value): void
    {
        static::$value = $value;
    }

    public static function reset(): void
    {
        static::$value = null;
    }
}

function file_get_contents()
{
    return Buffer::get();
}
