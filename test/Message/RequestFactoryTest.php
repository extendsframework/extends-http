<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message;

use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    /**
     * @covers \ExtendsFramework\Http\Message\RequestFactory::create()
     * @covers \ExtendsFramework\Http\Message\RequestFactory::__construct()
     * @covers \ExtendsFramework\Http\Message\RequestFactory::getBody()
     * @covers \ExtendsFramework\Http\Message\RequestFactory::getRawBody()
     * @covers \ExtendsFramework\Http\Message\RequestFactory::getHeaders()
     * @covers \ExtendsFramework\Http\Message\RequestFactory::getMethod()
     * @covers \ExtendsFramework\Http\Message\RequestFactory::getParameters()
     * @covers \ExtendsFramework\Http\Message\RequestFactory::getPath()
     */
    public function testCanCreateRequest(): void
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, '{"bar":"baz"}');

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = 'baz=qux';
        $_SERVER['REQUEST_URI'] = '/foo/bar';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

        $request = (new RequestFactory($resource))->create();

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('qux', $request->getParameters()->get('baz'));
        $this->assertSame('/foo/bar', $request->getPath());
        $this->assertSame('application/json', $request->getHeaders()->get('Content-Type'));
        $this->assertSame('baz', $request->getBody()->get('bar'));
    }

    /**
     * @covers                   \ExtendsFramework\Http\Message\RequestFactory::create()
     * @covers                   \ExtendsFramework\Http\Message\RequestFactory::__construct()
     * @covers                   \ExtendsFramework\Http\Message\RequestFactory::getBody()
     * @covers                   \ExtendsFramework\Http\Message\RequestFactory::getRawBody()
     * @covers                   \ExtendsFramework\Http\Message\Exception\InvalidRequest::forInvalidBody()
     * @expectedException        \ExtendsFramework\Http\Message\Exception\InvalidRequest
     * @expectedExceptionMessage Request body MUST be valid JSON, got error "Syntax error".
     */
    public function testCanNotCreateRequestWithInvalidJsonBody(): void
    {
        $resource = fopen('php://temp', 'wb+');
        fwrite($resource, '{"bar":"baz"');

        (new RequestFactory($resource))->create();
    }
}
