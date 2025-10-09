<?php

namespace App\DTO\Http\Request\MakePurchase;

use App\DTO\Http\Request\TaxNumberRequestTrait;
use Symfony\Component\Validator\Constraints as Assert;

class MakePurchaseRequest
{
    use TaxNumberRequestTrait;

    #[Assert\NotBlank]
    public int $product;

    public ?string $couponCode = null;
}
