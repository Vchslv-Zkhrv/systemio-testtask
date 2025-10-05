<?php

namespace App\DBAL\Types\Enum;

use App\DBAL\Types\EnumType;
use App\Enum\PurchaseStatusType;

class DbalPurchaseStatusType extends EnumType
{
    public static function getTypeName(): string
    {
        return 'purchase_status_enum';
    }

    public static function getEnumClass(): string
    {
        return PurchaseStatusType::class;
    }
}
