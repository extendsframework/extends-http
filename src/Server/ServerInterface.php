<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Server;

interface ServerInterface
{
    /**
     * Run server.
     *
     * @return void
     */
    public function run(): void;
}
