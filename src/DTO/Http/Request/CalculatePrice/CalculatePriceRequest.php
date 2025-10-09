<?php

namespace App\DTO\Http\Request\CalculatePrice;

use App\DTO\Http\Request\TaxNumberRequestTrait;
use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceRequest
{
    use TaxNumberRequestTrait;

    #[Assert\NotBlank]
    public int $product;

    public ?string $couponCode = null;
}
