<?php

namespace App\DTO\Http\Request\CalculatePrice;

class CalculatePriceResponse
{
    public function __construct(public readonly float $tax)
    {
    }
}
