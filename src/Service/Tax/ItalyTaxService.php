<?php

namespace App\Service\Tax;

use App\Entity\Country;

class ItalyTaxService extends TaxService
{
    public function __construct(protected readonly float $vat)
    {
    }

    public static function getCountryCode(): string
    {
        return Country::DOMAIN_ZONE_ITALY;
    }

    protected static function getTaxCodePattern(): string
    {
        return '/^IT\d{11}$/';
    }

    public function calculateTax(float $amount): float
    {
        return $amount * $this->vat;
    }
}
