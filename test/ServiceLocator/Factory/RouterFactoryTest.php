<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\ServiceLocator\Factory;

use ExtendsFramework\Http\Router\Route\RouteInterface;
use ExtendsFramework\Http\Router\Route\Scheme\SchemeRoute;
use ExtendsFramework\Http\Router\RouterInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RouterFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an instance of RouterInterface.
     *
     * @covers \ExtendsFramework\Http\ServiceLocator\Factory\RouterFactory::createService()
     */
    public function testCreateService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn([
                RouterInterface::class => [
                    'routes' => [
                        [
                            'name' => SchemeRoute::class,
                            'options' => [
                                'scheme' => 'https',
                                'parameters' => [
                                    'foo' => 'bar',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(SchemeRoute::class, [
                'scheme' => 'https',
                'parameters' => [
                    'foo' => 'bar',
                ],
            ])
            ->willReturn($this->createMock(RouteInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new RouterFactory();
        $router = $factory->createService(RouterInterface::class, $serviceLocator, []);

        $this->assertInstanceOf(RouterInterface::class, $router);
    }
}
