<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Router\Route\Method\Exception;

use PHPUnit\Framework\TestCase;

class MethodNotAllowedTest extends TestCase
{
    /**
     * Get allowed methods.
     *
     * Test that correct allowed methods will be returned.
     *
     * @covers \ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed::__construct()
     * @covers \ExtendsFramework\Http\Router\Route\Method\Exception\MethodNotAllowed::getAllowedMethods()
     */
    public function testGetAllowedMethods(): void
    {
        $exception = new MethodNotAllowed('GET', ['POST', 'PUT']);

        $this->assertSame([
            'POST',
            'PUT',
        ], $exception->getAllowedMethods());
    }
}
