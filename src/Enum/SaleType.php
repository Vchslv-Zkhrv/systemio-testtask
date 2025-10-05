<?php

namespace App\Enum;

enum SaleType: string
{
    case PERCENT = 'percent';
    case EXACT = 'exact';
}
