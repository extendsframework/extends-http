<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Request\Request::__construct()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::getBody()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers \ExtendsFramework\Http\Request\Request::getPath()
     * @covers \ExtendsFramework\Http\Request\Request::getQuery()
     */
    public function testCanCreateNewInstance(): void
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
        $this->assertSame('/foo/bar', $request->getPath());
        $this->assertSame('qux', $request->getQuery()->get('baz'));

        Content::clearContent();
    }

    /**
     * @covers                   \ExtendsFramework\Http\Request\Request::__construct()
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

    /**
     * @covers  \ExtendsFramework\Http\Request\Request::withAttribute()
     * @covers  \ExtendsFramework\Http\Request\Request::getAttributes()
     */
    public function testCanCreateNewInstanceWithAttribute(): void
    {
        $request = new Request();
        $clone = $request->withAttribute('foo', 'bar');

        $this->assertNotSame($request, $clone);
        $this->assertSame('bar', $clone->getAttributes()->get('foo'));
    }
}

abstract class Content
{
    /**
     * @var string
     */
    public static $content;

    /**
     * @param string $content
     * @return void
     */
    public static function setContent(string $content): void
    {
        self::$content = $content;
    }

    /**
     * @return void
     */
    public static function clearContent(): void
    {
        self::$content = null;
    }
}

function file_get_contents()
{
    return Content::$content;
}
