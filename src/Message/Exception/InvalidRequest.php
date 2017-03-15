<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Message\Exception;

use ExtendsFramework\Http\Message\RequestException;

class InvalidRequest extends RequestException
{
    /**
     * Return new instance with $error when post body is invalid JSON.
     *
     * @param string $error
     * @return RequestException
     */
    public static function forInvalidBody(string $error): RequestException
    {
        return new static(\sprintf(
            'Request body MUST be valid JSON, got error "%s".',
            $error
        ));
    }
}
