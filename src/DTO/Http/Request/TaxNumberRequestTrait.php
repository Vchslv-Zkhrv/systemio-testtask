<?php

namespace App\DTO\Http\Request;

use App\Exception\InvalidTaxCodeException;
use App\Facade\TaxFacade;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

trait TaxNumberRequestTrait
{
    protected string $country;

    #[Assert\NotBlank]
    public string $taxNumber;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
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
