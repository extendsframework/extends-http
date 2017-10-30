<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Renderer\Json;

use ExtendsFramework\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class JsonRendererTest extends TestCase
{
    /**
     * Render.
     *
     * Test that response will be rendered: headers sent, body encoded and HTTP status code set.
     *
     * @covers \ExtendsFramework\Http\Renderer\Json\JsonRenderer::render()
     */
    public function testRender(): void
    {
        Buffer::reset();

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn([
                'foo' => 'bar',
            ]);

        $response
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
                'Accept' => 'text/plain',
                'Accept-Charset' => 'utf-8',
            ]);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        /**
         * @var ResponseInterface $response
         */
        $renderer = new JsonRenderer();

        ob_start();
        $renderer->render($response);
        $output = ob_get_clean();

        $this->assertSame('{"foo":"bar"}', $output);
        $this->assertSame([
            'Accept: text/plain',
            'Accept-Charset: utf-8',
        ], Buffer::getHeaders());
        $this->assertSame(200, Buffer::getCode());
    }
}

class Buffer
{
    protected static $headers = [];

    protected static $code;

    public static function getHeaders(): array
    {
        return self::$headers;
    }

    public static function getCode(): int
    {
        return self::$code;
    }

    public static function addHeader(string $header): void
    {
        self::$headers[] = $header;
    }

    public static function setCode($code): void
    {
        self::$code = $code;
    }

    public static function reset(): void
    {
        static::$headers = [];
        static::$code = null;
    }
}

function header($header)
{
    Buffer::addHeader($header);
}

function http_response_code($code)
{
    Buffer::setCode($code);
}
