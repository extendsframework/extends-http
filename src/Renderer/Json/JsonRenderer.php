<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Renderer\Json;

use ExtendsFramework\Http\Renderer\RendererInterface;
use ExtendsFramework\Http\Response\ResponseInterface;

class JsonRenderer implements RendererInterface
{
    /**
     * @inheritDoc
     */
    public function render(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $header => $value) {
            header(sprintf(
                '%s: %s',
                $header,
                $value
            ));
        }

        http_response_code($response->getStatusCode());
        echo json_encode($response->getBody(), JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
}
