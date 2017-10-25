<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test that get parameters will return default values.
     *
     * @covers \ExtendsFramework\Http\Response\Response::getBody()
     * @covers \ExtendsFramework\Http\Response\Response::getHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::getStatusCode()
     */
    public function testGetMethods(): void
    {
        $response = new Response();

        $this->assertSame([], $response->getBody());
        $this->assertSame([], $response->getHeaders());
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * And methods.
     *
     * Test that new responses will be returned with the correct values.
     *
     * @covers \ExtendsFramework\Http\Response\Response::withHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::andHeader()
     * @covers \ExtendsFramework\Http\Response\Response::getHeaders()
     */
    public function testAndMethods(): void
    {
        $response = (new Response())
            ->andHeader('baz', 'qux')
            ->andHeader('foo', 'bar');

        $this->assertSame([
            'baz' => 'qux',
            'foo' => 'bar',
        ], $response->getHeaders());
    }

    /**
     * With methods.
     *
     * Test that with methods can set value and return copy of response.
     *
     * @covers \ExtendsFramework\Http\Response\Response::withBody()
     * @covers \ExtendsFramework\Http\Response\Response::withHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::withStatusCode()
     * @covers \ExtendsFramework\Http\Response\Response::getBody()
     * @covers \ExtendsFramework\Http\Response\Response::getHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::getStatusCode()
     */
    public function testWithMethods(): void
    {
        $response = (new Response())
            ->withBody(['foo' => 'bar'])
            ->withHeaders(['baz' => 'qux'])
            ->withStatusCode(201);

        $this->assertSame(['foo' => 'bar'], $response->getBody());
        $this->assertSame(['baz' => 'qux'], $response->getHeaders());
        $this->assertSame(201, $response->getStatusCode());
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of ResponseInterface.
     *
     * @covers \ExtendsFramework\Http\Response\Response::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $request = Response::factory(ResponseInterface::class, $serviceLocator);

        $this->assertInstanceOf(ResponseInterface::class, $request);
    }
}
