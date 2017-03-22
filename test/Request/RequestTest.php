<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Container\ContainerInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::getBody()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testCanCreateNewInstanceFromEnvironment(): void
    {
        Content::setContent('{"foo":"qux"}');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = 'baz=qux';
        $_SERVER['REQUEST_URI'] = '/foo/bar';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

        $request = new Request();

        $this->assertInstanceOf(ContainerInterface::class, $request->getAttributes());
        $this->assertSame('qux', $request->getBody()->get('foo'));
        $this->assertSame('application/json', $request->getHeaders()->get('Content-Type'));
        $this->assertSame('POST', $request->getMethod());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());

        Content::clearContent();
    }

    /**
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
    public function testCanGetFromWithMethods(): void
    {
        $attributes = $this->createMock(ContainerInterface::class);

        $body = $this->createMock(ContainerInterface::class);

        $headers = $this->createMock(ContainerInterface::class);

        $uri = $this->createMock(UriInterface::class);

        /**
         * @var ContainerInterface $attributes
         * @var ContainerInterface $body
         * @var ContainerInterface $headers
         * @var UriInterface       $uri
         */
        $request = (new Request())
            ->withAttributes($attributes)
            ->withBody($body)
            ->withHeaders($headers)
            ->withMethod('POST')
            ->withUri($uri);

        $this->assertSame($attributes, $request->getAttributes());
        $this->assertSame($body, $request->getBody());
        $this->assertSame($headers, $request->getHeaders());
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
    }

    /**
     * @covers \ExtendsFramework\Http\Request\Request::withAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::andAttribute()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     */
    public function testCanMergeWithAttribute(): void
    {
        $attributes = $this->createMock(ContainerInterface::class);
        $attributes
            ->expects($this->once())
            ->method('with')
            ->with('foo', 'bar')
            ->willReturnSelf();

        /**
         * @var ContainerInterface $attributes
         */
        $uri = (new Request())
            ->withAttributes($attributes)
            ->andAttribute('foo', 'bar');

        $this->assertSame($attributes, $uri->getAttributes());
    }

    /**
     * @covers \ExtendsFramework\Http\Request\Request::withHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::andHeader()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     */
    public function testCanMergeWithHeader(): void
    {
        $headers = $this->createMock(ContainerInterface::class);
        $headers
            ->expects($this->once())
            ->method('with')
            ->with('foo', 'bar')
            ->willReturnSelf();

        /**
         * @var ContainerInterface $headers
         */
        $uri = (new Request())
            ->withHeaders($headers)
            ->andHeader('foo', 'bar');

        $this->assertSame($headers, $uri->getHeaders());
    }

    /**
     * @covers                   \ExtendsFramework\Http\Request\Request::getBody()
     * @covers                   \ExtendsFramework\Http\Request\Exception\InvalidRequest::forInvalidBody()
     * @expectedException        \ExtendsFramework\Http\Request\Exception\InvalidRequest
     * @expectedExceptionMessage Request body MUST be valid JSON; Syntax error.
     */
    public function testCanNotCreateFromEnvironmentWithInvalidBody(): void
    {
        Content::setContent('{"foo":"qux"');

        $request = new Request();
        $request->getBody();

        Content::clearContent();
    }
}

abstract class Content
{
    /**
     * @var string
     */
    public static $content;

    /**
     * @return void
     */
    public static function clearContent(): void
    {
        self::$content = null;
    }

    /**
     * @param string $content
     * @return void
     */
    public static function setContent(string $content): void
    {
        self::$content = $content;
    }
}

function file_get_contents()
{
    return Content::$content;
}
