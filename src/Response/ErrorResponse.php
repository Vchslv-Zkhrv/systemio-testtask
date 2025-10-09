<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends JsonResponse
{
    public static function build(string $message, int $status = 400): static
    {
        return new static(
            [
                'ok' => false,
                'message' => $message 
            ],
            status: Response::HTTP_BAD_REQUEST
        );
    }
}
