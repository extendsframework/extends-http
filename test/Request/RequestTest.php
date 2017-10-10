<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Http\Request\Uri\UriInterface;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return the correct php://input abd $_SERVER values.
     *
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::getBody()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testCanCreateNewInstanceFromEnvironment(): void
    {
        Buffer::set('{"foo":"qux"}');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = 'baz=qux';
        $_SERVER['REQUEST_URI'] = '/foo/bar';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

        $request = new Request();

        $this->assertSame([], $request->getAttributes());
        $this->assertSame(['foo' => 'qux',], $request->getBody());
        $this->assertSame(['Content-Type' => 'application/json'], $request->getHeaders());
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
     * @covers  \ExtendsFramework\Http\Request\Request::getBody()
     * @covers  \ExtendsFramework\Http\Request\Request::getHeaders()
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
        $uri = (new Request())
            ->andAttribute('foo', 'bar')
            ->andAttribute('qux', 'quux');

        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $uri->getAttributes());
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
        $uri = (new Request())
            ->andHeader('foo', 'bar')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $uri->getHeaders());
    }

    /**
     * Invalid body.
     *
     * Test that invalid body can not be parsed and a exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Http\Request\Request::getBody()
     * @covers                   \ExtendsFramework\Http\Request\Exception\InvalidRequestBody::__construct()
     * @expectedException        \ExtendsFramework\Http\Request\Exception\InvalidRequestBody
     * @expectedExceptionMessage Invalid JSON for request body, got parse error "Syntax error".
     */
    public function testInvalidBody(): void
    {
        Buffer::set('{"foo":"qux"');

        $request = new Request();
        $request->getBody();

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
