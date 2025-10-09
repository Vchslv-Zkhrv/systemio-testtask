<?php

namespace App\Service\Payment;

use App\Entity\Purchase;
use App\Enum\PaymentSystemType;
use App\Exception\PaymentException;

abstract class PaymentService
{
    abstract public static function getPaymentSystem(): PaymentSystemType;

    /**
     * @param Purchase $purchase
     *
     * @return void
     *
     * @throws PaymentException
     */
    abstract public function processPurchase(Purchase $purchase): void;
}
