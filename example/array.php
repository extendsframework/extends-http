<?php
declare(strict_types=1);

$paths = explode('/', '/foo/:bar/baz/:qux');
$nodes = explode('/', '/foo/5/baz/19');
$constraints = [
    'bar' => '\d+',
];

$parameters = [];
foreach ($paths as $index => $path) {
    if (strpos($path, ':') === 0) {
        $path = substr($path, 1);
        if (array_key_exists($path, $constraints) === true) {
            $regex = '~^' . $constraints[$path] . '$~';
            if (preg_match($regex, $nodes[$index]) === 0) {
                throw new InvalidArgumentException(sprintf(
                    'Value "%s" for segment "%s" does not match constraint "%s".',
                    $nodes[$index],
                    $path,
                    $constraints[$path]
                ));
            }
        }

        $parameters[$path] = $nodes[$index];
    } elseif ($path !== $nodes[$index]) {
        throw new InvalidArgumentException(sprintf(
            '%s not equals %s.',
            $path,
            $nodes[$index]
        ));
    }
}

var_dump($parameters);
