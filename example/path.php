<?php
declare(strict_types=1);

use ExtendsFramework\Http\Router\Route\Path\PathRoute;
use ExtendsFramework\Validator\Constraint\Type\NumericConstraint;

require_once __DIR__ . '/../vendor/autoload.php';

$pathRoute = new PathRoute('/foo/:id/bar', [
    'id' => new NumericConstraint(),
]);

