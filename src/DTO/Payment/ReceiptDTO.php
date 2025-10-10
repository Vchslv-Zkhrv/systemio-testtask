<?php

namespace App\DTO\Payment;

use App\Enum\PaymentSystemType;

class ReceiptDTO
{
    /**
     * @param ReceiptProductDTO[] $products
     */
    public function __construct(
        public readonly string $taxNumber,
        public readonly array $products,
        public readonly float $grossPrice,
        public readonly float $tax,
        public readonly float $sale,
        public readonly float $totalPrice,
        public readonly PaymentSystemType $paymentProcessor,
    ) {
    }
}
