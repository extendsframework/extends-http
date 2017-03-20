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
    public function testCanCreateNewInstance(): RequestInterface
    {
        $attributes = $this->createMock(ContainerInterface::class);

        $body = $this->createMock(ContainerInterface::class);

        $headers = $this->createMock(ContainerInterface::class);

        $query = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $attributes
         * @var ContainerInterface $body
         * @var ContainerInterface $headers
         * @var ContainerInterface $query
         */
        $request = new Request($attributes, $body, $headers, 'GET', '/foo/bar', $query);

        $this->assertSame($attributes, $request->getAttributes());
        $this->assertSame($body, $request->getBody());
        $this->assertSame($headers, $request->getHeaders());
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/foo/bar', $request->getPath());
        $this->assertSame($query, $request->getQuery());

        return $request;
    }

    /**
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::getBody()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers \ExtendsFramework\Http\Request\Request::getPath()
     * @covers \ExtendsFramework\Http\Request\Request::getQuery()
     */
    public function testCanCreateFromEnvironment()
    {
        Content::setContent('{"foo":"qux"}');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/foo/bar?baz=qux';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

        $request = Request::fromEnvironment();

        $this->assertInstanceOf(ContainerInterface::class, $request->getAttributes());
        $this->assertSame('qux', $request->getBody()->get('foo'));
        $this->assertSame('application/json', $request->getHeaders()->get('Content-Type'));
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/foo/bar', $request->getPath());
        $this->assertSame('qux', $request->getQuery()->get('baz'));

        Content::clearContent();
    }

    /**
     * @covers                   \ExtendsFramework\Http\Request\Request::fromEnvironment()
     * @covers                   \ExtendsFramework\Http\Request\Exception\InvalidRequest::forInvalidBody()
     * @expectedException        \ExtendsFramework\Http\Request\Exception\InvalidRequest
     * @expectedExceptionMessage Request body MUST be valid JSON; Syntax error.
     */
    public function testCanNotCreateFromEnvironmentWithInvalidBody()
    {
        Content::setContent('{"foo":"qux"');

        Request::fromEnvironment();

        Content::clearContent();
    }

    /**
     * @param RequestInterface $request
     * @depends testCanCreateNewInstances
     * @covers  \ExtendsFramework\Http\Request\Request::withAttribute()
     * @covers  \ExtendsFramework\Http\Request\Request::getAttributes()
     */
    public function testCanCreateNewInstanceWithAttribute(RequestInterface $request): void
    {
        $clone = $request->withAttribute('foo', 'bar');

        $this->assertNotSame($request, $clone);
        $this->assertSame('bar', $request->getAttributes()->get('foo'));
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
