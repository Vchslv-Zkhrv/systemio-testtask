<?php

namespace App\Enum;

enum PaymentSystemType: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
}
