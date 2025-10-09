<?php

namespace App\DTO\Http\Response\CalculatePrice;

class CalculatePriceResponse
{
    public function __construct(public readonly float $price)
    {
    }
}
