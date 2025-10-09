<?php

namespace App\DTO\Http\Request\CalculatePrice;

use App\Exception\InvalidTaxCodeException;
use App\Facade\TaxFacade;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CalculatePriceRequest
{
    protected string $country;

    #[Assert\NotBlank]
    public int $product;

    #[Assert\NotBlank]
    public string $taxNumber;

    public ?string $couponCode = null;

    #[Assert\Callback]
    public function validateTaxNumber(ExecutionContextInterface $context): void
    {
        if (isset($this->taxNumber)) {
            try {
                $this->country = TaxFacade::detectCountryByTaxCode($this->taxNumber);
            } catch (InvalidTaxCodeException $te) {
                $context->buildViolation($te->getMessage())->atPath('taxNumber')->addViolation();
            }
        }
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
