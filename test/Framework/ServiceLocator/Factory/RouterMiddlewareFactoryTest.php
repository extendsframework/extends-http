<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Factory;

use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\Http\Framework\Http\Middleware\Router\RouterMiddleware;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RouterMiddlewareFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an instance of RouterMiddleware.
     *
     * @covers \ExtendsFramework\Http\Framework\ServiceLocator\Factory\RouterMiddlewareFactory::createService()
     */
    public function testCreateService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(RouterInterface::class)
            ->willReturn($this->createMock(RouterInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new RouterMiddlewareFactory();
        $router = $factory->createService(RouterMiddleware::class, $serviceLocator, []);

        $this->assertInstanceOf(RouterMiddleware::class, $router);
    }
}
