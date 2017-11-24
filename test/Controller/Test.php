<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class FakeStub
{
    public function someFancyAction(): void
    {
    }
}

class FooTest extends TestCase
{
    public function testBar(): void
    {
        $mock = $this->createMock(FakeStub::class);
        $mock
            ->expects($this->once()) // Can not auto complete expects() method, PhpStorm thinks $mock is an instance of FakeStub.
            ->method('someFancyAction');

        /**
         * @var FakeStub $mock
         */
        $mock->someFancyAction(); // Can auto complete someFancyAction() method.
    }
}
