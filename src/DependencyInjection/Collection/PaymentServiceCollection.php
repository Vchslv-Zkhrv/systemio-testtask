<?php

namespace App\DependencyInjection\Collection;

use App\Enum\PaymentSystemType;
use App\Exception\PaymentException;
use App\Service\Payment\PaymentService;

/**
 * @extends Collection<string,PaymentService>
 */
class PaymentServiceCollection extends Collection
{
    public function get(PaymentSystemType $paymentSystem): PaymentService
    {
        $service = $this->items[$paymentSystem->value] ?? null;
        if ($service === null) {
            throw new PaymentException("Unknown payment system: $paymentSystem->value");
        }

        return $service;
    }
}
