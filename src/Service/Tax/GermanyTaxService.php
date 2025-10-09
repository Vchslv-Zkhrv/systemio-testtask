<?php

namespace App\Service\Tax;

use App\Entity\Country;

class GermanyTaxService extends TaxService
{
    public function __construct(protected readonly float $vat)
    {
    }

    public static function getCountryCode(): string
    {
        return Country::DOMAIN_ZONE_GERMANY;
    }

    protected static function getTaxCodePattern(): string
    {
        return '/^DE\d{9}$/';
    }

    public function calculateTax(float $amount): float
    {
        return $amount * $this->vat;
    }
}
