<?php

namespace App\DTO\Payment;

class ReceiptProductDTO
{
    public function __construct(
        public readonly int $article,
        public readonly string $name,
        public readonly int $quantity,
    ) {
    }
}
