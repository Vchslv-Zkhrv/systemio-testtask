<?php

namespace App\Service\Payment;

use App\Entity\Purchase;
use App\Enum\PaymentSystemType;
use App\Exception\PaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentService extends PaymentService
{
    public function __construct(protected PaypalPaymentProcessor $paymentProcessor)
    {
    }

    public static function getPaymentSystem(): PaymentSystemType
    {
        return PaymentSystemType::PAYPAL;
    }

    public function processPurchase(Purchase $purchase): void
    {
        $cents = (int)($purchase->getGrossTotal() * 100);

        try {
            $this->paymentProcessor->pay($cents);
        } catch (\Exception $e) {
            throw new PaymentException($e->getMessage(), previous: $e);
        }
    }
}
