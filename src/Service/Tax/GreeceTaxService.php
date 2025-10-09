<?php

namespace App\Service\Tax;

use App\Entity\Country;

class GreeceTaxService extends TaxService
{
    public function __construct(protected readonly float $vat)
    {
    }

    public static function getCountryCode(): string
    {
        return Country::DOMAIN_ZONE_GREECE;
    }

    protected static function getTaxCodePattern(): string
    {
        return '/^GR\d{9}$/';
    }

    public function calculateTax(float $amount): float
    {
        return $amount * $this->vat;
    }
}
