<?php

namespace App\Service\Payment;

use App\Entity\Purchase;
use App\Enum\PaymentSystemType;
use App\Exception\PaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentService extends PaymentService
{
    public function __construct(protected StripePaymentProcessor $paymentProcessor)
    {
    }

    public static function getPaymentSystem(): PaymentSystemType
    {
        return PaymentSystemType::STRIPE;
    }

    public function processPurchase(Purchase $purchase): void
    {
        $result = $this->paymentProcessor->processPayment($purchase->getGrossTotal());

        if ($result === false) {
            throw new PaymentException("Cannot process payment: purchase gross total is lower than 1");
        }
    }
}
