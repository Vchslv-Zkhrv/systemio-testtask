<?php

namespace App\Service\Tax;

use App\Entity\Purchase;
use App\Service\Coupon\CouponService;

abstract class TaxService
{
    abstract protected static function getTaxCodePattern(): string;

    abstract public static function getCountryCode(): string;

    abstract public function calculateTax(float $amount): float;

    public function calculatePurchaseTax(Purchase $purchase): float
    {
        $sale = CouponService::calculatePurchaseSale($purchase);
        return $this->calculateTax($purchase->getGrossTotal() - $sale);
    }

    public static function checkTaxCodeIsValid(string $taxCode): bool
    {
        preg_match(static::getTaxCodePattern(), $taxCode, $matches);
        return !empty($matches);
    }
}
