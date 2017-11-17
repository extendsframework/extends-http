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

        $this->assertSame(null, $response->getBody());
        $this->assertSame([], $response->getHeaders());
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * And methods.
     *
     * Test that new responses will be returned with the correct values.
     *
     * @covers \ExtendsFramework\Http\Response\Response::withBody()
     * @covers \ExtendsFramework\Http\Response\Response::andBody()
     * @covers \ExtendsFramework\Http\Response\Response::getBody()
     * @covers \ExtendsFramework\Http\Response\Response::withHeaders()
     * @covers \ExtendsFramework\Http\Response\Response::andHeader()
     * @covers \ExtendsFramework\Http\Response\Response::getHeaders()
     */
    public function testAndMethods(): void
    {
        $response = (new Response())
            ->withBody([
                'foo' => 'baz',
            ])
            ->andBody([
                'foo' => 'bar',
            ])
            ->andBody([
                'qux' => 'quux',
            ])
            ->andHeader('baz', 'qux')
            ->andHeader('baz', 'bar')
            ->andHeader('foo', 'bar');

        $this->assertSame([
            'baz' => [
                'qux',
                'bar',
            ],
            'foo' => 'bar',
        ], $response->getHeaders());
        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $response->getBody());
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
     * With headers.
     *
     * Test that already set header will be overwritten.
     *
     * @covers \ExtendsFramework\Http\Response\Response::andHeader()
     * @covers \ExtendsFramework\Http\Response\Response::withHeader()
     * @covers \ExtendsFramework\Http\Response\Response::getHeaders()
     */
    public function testWithHeader(): void
    {
        $response = (new Response())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->withHeader('foo', 'qux')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => 'qux',
            'qux' => 'quux',
        ], $response->getHeaders());
    }

    /**
     * With problem.
     *
     * Test that correct problem details will be set.
     *
     * @covers \ExtendsFramework\Http\Response\Response::withProblem()
     * @covers \ExtendsFramework\Http\Response\Response::andBody()
     * @covers \ExtendsFramework\Http\Response\Response::getStatusCode()
     * @covers \ExtendsFramework\Http\Response\Response::getBody()
     */
    public function testWithProblem()
    {
        $response = (new Response())
            ->withProblem(429, 'https://www.example.com/docs/invalid-data', 'Invalid data.')
            ->andBody([
                'errors' => [
                    'property' => 'first_name',
                    'reason' => 'Can not be empty.',
                ],
            ]);

        $this->assertSame([
            'type' => 'https://www.example.com/docs/invalid-data',
            'title' => 'Invalid data.',
            'errors' => [
                'property' => 'first_name',
                'reason' => 'Can not be empty.',
            ],
        ], $response->getBody());
        $this->assertSame(429, $response->getStatusCode());

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
