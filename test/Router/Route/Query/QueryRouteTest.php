<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Query;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\Query\Exception\InvalidQueryString;
use ExtendsFramework\Http\Router\Route\Query\Exception\QueryParameterMissing;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Constraint\ConstraintInterface;
use ExtendsFramework\Validator\Constraint\ConstraintViolationInterface;
use PHPUnit\Framework\TestCase;

class QueryRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that route will match '?limit=20&offset=0' and return an instance of RouteMatchInterface
     *
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::getParameters()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'offset' => '0',
                'limit' => '20',
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $constraint = $this->createMock(ConstraintInterface::class);
        $constraint
            ->expects($this->exactly(2))
            ->method('validate')
            ->withConsecutive(
                ['20'],
                ['0']
            )
            ->willReturn(null);

        /**
         * @var RequestInterface $request
         */
        $path = new QueryRoute([
            'limit' => $constraint,
            'offset' => $constraint,
        ], [
            'offset' => '0',
        ]);
        $match = $path->match($request, 4);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(4, $match->getPathOffset());
            $this->assertSame([
                'offset' => '0',
                'limit' => '20',
            ], $match->getParameters());
        }
    }

    /**
     * No match.
     *
     * Test that route will not match empty query and return null.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Query\Exception\InvalidQueryString::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\Exception\InvalidQueryString::getParameter()
     * @covers \ExtendsFramework\Http\Router\Route\Query\Exception\InvalidQueryString::getViolation()
     */
    public function testNoMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'limit' => 'foo',
                'offset' => 'bar',
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $violation = $this->createMock(ConstraintViolationInterface::class);
        $violation
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('Some fancy reason!');

        $constraint = $this->createMock(ConstraintInterface::class);
        $constraint
            ->expects($this->once())
            ->method('validate')
            ->with('foo')
            ->willReturn($violation);

        /**
         * @var RequestInterface $request
         */
        $path = new QueryRoute([
            'limit' => $constraint,
            'offset' => $constraint,
        ]);

        try {
            $path->match($request, 4);
        } catch (InvalidQueryString $exception) {
            $this->assertSame(
                'Query string parameter "limit" failed due to violation "Some fancy reason!".',
                $exception->getMessage()
            );
            $this->assertSame('limit', $exception->getParameter());
            $this->assertSame($violation, $exception->getViolation());
        }
    }

    /**
     * Constraint without default.
     *
     * Test that a missing query parameter, without default value, will thrown an exception.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Query\Exception\QueryParameterMissing::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Query\Exception\QueryParameterMissing::getParameter()
     */
    public function testConstraintWithoutDefault(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'limit' => 20,
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $constraint = $this->createMock(ConstraintInterface::class);

        /**
         * @var RequestInterface $request
         */
        $path = new QueryRoute([
            'limit' => $constraint,
            'offset' => $constraint,
        ]);

        try {
            $path->match($request, 4);
        } catch (QueryParameterMissing $exception) {
            $this->assertSame('offset', $exception->getParameter());
        }
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Query\QueryRoute::__construct()
     */
    public function testFactory(): void
    {
        $constraint = $this->createMock(ConstraintInterface::class);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->exactly(2))
            ->method('getService')
            ->withConsecutive(
                [ConstraintInterface::class, ['foo' => 'bar',]],
                [ConstraintInterface::class, []]
            )
            ->willReturn($constraint);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = QueryRoute::factory(QueryRoute::class, $serviceLocator, [
            'path' => '/:id/bar',
            'constraints' => [
                'limit' => [
                    'name' => ConstraintInterface::class,
                    'options' => [
                        'foo' => 'bar',
                    ],
                ],
                'offset' => ConstraintInterface::class, // Short syntax will be converted to array with 'name' property.
            ],
            'parameters' => [
                'offset' => '0',
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
