<?php

namespace App\Service\Tax;

use App\Entity\Country;

class FranceTaxService extends TaxService
{
    public function __construct(protected readonly float $vat)
    {
    }

    public static function getCountryCode(): string
    {
        return Country::DOMAIN_ZONE_FRANCE;
    }

    protected static function getTaxCodePattern(): string
    {
        return '/^FR[A-Z]{2}\d{9}$/';
    }

    public function calculateTax(float $amount): float
    {
        return $amount * $this->vat;
    }
}
