<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * Build response using object
     *
     * @param object               $data
     * @param int                  $status
     * @param array<string,string> $headers
     *
     * @return static
     */
    public static function build(object $data, int $status = 200, array $headers = []): static
    {
        $body = [
            'ok' => $status < 400,
            'data' => $data
        ];
        return new static($body, $status, $headers);
    }
}
