<?php
declare(strict_types=1);

$parameters = [
    'foo' => 'bar',
    'baz' => 'qux',
];

$query = [
    'foo' => 'bar',
    'qux' => 'quux',
];

var_dump(array_diff_key($query, $parameters));
