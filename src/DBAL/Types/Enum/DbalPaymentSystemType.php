<?php

namespace App\DBAL\Types\Enum;

use App\DBAL\Types\EnumType;
use App\Enum\PaymentSystemType;

class DbalPaymentSystemType extends EnumType
{
    public static function getTypeName(): string
    {
        return 'payment_system_enum';
    }

    public static function getEnumClass(): string
    {
        return PaymentSystemType::class;
    }
}
