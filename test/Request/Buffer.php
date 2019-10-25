<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

class Buffer
{
    /**
     * @var
     */
    protected static $value;

    /**
     * Get value.
     *
     * @return string
     */
    public static function get(): string
    {
        return static::$value;
    }

    /**
     * Set value.
     *
     * @param string $value
     * @return void
     */
    public static function set(string $value): void
    {
        static::$value = $value;
    }

    /**
     * Reset value;
     *
     * @return void
     */
    public static function reset(): void
    {
        static::$value = null;
    }
}
