<?php

namespace App\DBAL\Types\Enum;

use App\DBAL\Types\EnumType;
use App\Enum\SaleType;

class DbalSaleType extends EnumType
{
    public static function getTypeName(): string
    {
        return 'sale_type_enum';
    }

    public static function getEnumClass(): string
    {
        return SaleType::class;
    }
}
