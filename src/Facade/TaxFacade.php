<?php

namespace App\Facade;

use App\DependencyInjection\Collection\TaxServiceCollection;
use App\Entity\Purchase;
use App\Entity\User;
use App\Exception\CannotCalculateTaxException;
use App\Exception\InvalidTaxCodeException;
use App\Repository\UserRepository;
use App\Service\Tax\GreeceTaxService;
use App\Service\Tax\FranceTaxService;
use App\Service\Tax\ItalyTaxService;
use App\Service\Tax\GermanyTaxService;
use App\Service\Tax\TaxService;
use App\Entity\Country;

/**
 * Wraps TaxService implementations
 */
class TaxFacade
{
    /**
     * @var array<string,TaxService>
     */
    protected array $taxServices;

    public function __construct(
        protected UserRepository $userRepository,
        TaxServiceCollection $taxServiceCollection,
    ) {
        $this->taxServices = $taxServiceCollection->toArray();
    }

    /**
     * @return array<string,class-string<TaxService>>
     */
    protected static function getServiceClasses(): array
    {
        return [
            Country::DOMAIN_ZONE_GERMANY => GermanyTaxService::class,
            Country::DOMAIN_ZONE_ITALY => ItalyTaxService::class,
            Country::DOMAIN_ZONE_FRANCE => FranceTaxService::class,
            Country::DOMAIN_ZONE_GREECE => GreeceTaxService::class,
        ];
    }

    /**
     * @param Purchase     $purchase
     * @param null|Country $country  purchaser's country by default
     *
     * @return float
     *
     * @throws CannotCalculateTaxException
     */
    public function calculatePurchaseTax(
        Purchase $purchase,
        ?Country $country = null,
    ): float {
        $country = $country ?? $purchase->getPurchaser()->getCountry();
        if ($country === null) {
            throw new CannotCalculateTaxException("Cannot calculate tax: no country specified");
        }

        $taxService = $this->taxServices[$country->getDomainZone()];
        return round($taxService->calculatePurchaseTax($purchase), 2);
    }

    /**
     * @param string $taxCode
     *
     * @return string - country domain zone
     *
     * @throws InvalidTaxCodeException
     */
    public static function detectCountryByTaxCode(string $taxCode): string
    {
        foreach (static::getServiceClasses() as $serviceClass) {
            if ($serviceClass::checkTaxCodeIsValid($taxCode)) {
                return $serviceClass::getCountryCode();
            }
        }

        throw new InvalidTaxCodeException("Cannot detect country by tax code '$taxCode'");
    }

    /**
     * @param Country|string $country country or it's domain zone
     * @param string         $taxCode
     *
     * @return bool
     *
     * @throws InvalidTaxCodeException
     */
    public static function validateTaxCode(
        Country|string $country,
        string $taxCode
    ): bool {
        $domainZone = is_string($country) ? $country : $country->getDomainZone();

        $classes = static::getServiceClasses();
        if (!array_key_exists($domainZone, $classes)) {
            throw new InvalidTaxCodeException("Unknown domain zone '$domainZone'");
        }

        return $classes[$domainZone]::checkTaxCodeIsValid($taxCode);
    }

    public function getUserByTaxCode(string $taxCode): ?User
    {
        return $this->userRepository->findOneBy(['taxCode' => $taxCode]);
    }
}
