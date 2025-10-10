<?php

namespace App\DTO\Http\Request\MakePurchase;

use App\DTO\Http\Request\TaxNumberRequestTrait;
use App\Enum\PaymentSystemType;
use Symfony\Component\Validator\Constraints as Assert;

class MakePurchaseRequest
{
    use TaxNumberRequestTrait;

    #[Assert\NotBlank]
    public int $product;

    public ?string $couponCode = null;

    public ?PaymentSystemType $paymentProcessor = null;
}
