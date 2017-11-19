<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Path;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Constraint\ConstraintInterface;
use ExtendsFramework\Validator\Constraint\ConstraintViolationInterface;
use PHPUnit\Framework\TestCase;

class PathRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that path '/foo/:first_name/bar' will match '/:first_name/bar' and return an instance of
     * RouteMatchInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getParameters()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo/John/bar');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $constraint = $this->createMock(ConstraintInterface::class);
        $constraint
            ->expects($this->once())
            ->method('validate')
            ->with('John')
            ->willReturn(null);

        /**
         * @var RequestInterface $request
         */
        $path = new PathRoute('/:first_name/bar', [
            'first_name' => $constraint,
        ], [
            'foo' => 'bar',
        ]);
        $match = $path->match($request, 4);

        $this->assertInstanceOf(RouteMatchInterface::class, $match);
        if ($match instanceof RouteMatchInterface) {
            $this->assertSame(13, $match->getPathOffset());
            $this->assertSame([
                'foo' => 'bar',
                'first_name' => 'John',
            ], $match->getParameters());
        }
    }

    /**
     * No match.
     *
     * Test that path will not match.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     */
    public function testNotMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $constraint = $this->createMock(ConstraintInterface::class);
        $constraint
            ->expects($this->never())
            ->method('validate');

        /**
         * @var RequestInterface $request
         */
        $path = new PathRoute('/bar/:id', [
            'id' => $constraint,
        ]);
        $match = $path->match($request, 0);

        $this->assertNull($match);
    }

    /**
     * Invalid constraint.
     *
     * Test that constraint for 'id' is invalid and route won't match.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::match()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::getPattern()
     */
    public function testInvalidConstraint(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $constraint = $this->createMock(ConstraintInterface::class);
        $constraint
            ->expects($this->once())
            ->method('validate')
            ->with('foo')
            ->willReturn($this->createMock(ConstraintViolationInterface::class));

        /**
         * @var RequestInterface $request
         */
        $path = new PathRoute('/:id/bar', [
            'id' => $constraint,
        ]);
        $match = $path->match($request, 0);

        $this->assertNull($match);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::factory()
     * @covers \ExtendsFramework\Http\Router\Route\Path\PathRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(
                ConstraintInterface::class,
                ['foo' => 'bar']
            )
            ->willReturn($this->createMock(ConstraintInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = PathRoute::factory(PathRoute::class, $serviceLocator, [
            'path' => '/:id/bar',
            'constraints' => [
                'id' => [
                    'name' => ConstraintInterface::class,
                    'options' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            'defaults' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
