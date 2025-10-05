<?php

namespace App\Entity;

use App\Enum\SaleType;
use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\Table('coupons')]
class Coupon
{
    #[ORM\Id]
    #[ORM\Column('id', type: 'uuid')]
    private Uuid $id;

    #[ORM\Column('sale_type', type: 'sale_type_enum')]
    private SaleType $saleType;

    #[ORM\Column('sale_value')]
    private float $saleValue;

    #[ORM\Column('created_at')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column('valid_till', nullable: true)]
    private ?\DateTimeImmutable $validTill = null;

    #[ORM\Column(length: 255)]
    private string $code;

    #[ORM\ManyToOne(User::class, inversedBy: 'coupons')]
    #[ORM\JoinColumn('receiver_id', referencedColumnName: 'id', nullable: true, onDelete: 'RESTRICT')]
    private ?User $receiver = null;

    #[ORM\ManyToOne(Purchase::class, inversedBy: 'coupons')]
    #[ORM\JoinColumn('purchase_id', referencedColumnName: 'id', nullable: true, onDelete: 'RESTRICT')]
    private ?Purchase $purchase = null;

    public function __construct(
        Uuid $id,
        SaleType $saleType,
        float $saleValue,
        string $code,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $validTill = null,
    ) {
        $this->id = $id;
        $this->saleType = $saleType;
        $this->saleValue = $saleValue;
        $this->code = $code;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->validTill = $validTill;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSaleType(): SaleType
    {
        return $this->saleType;
    }

    public function getSaleValue(): float
    {
        return $this->saleValue;
    }

    public function setSaleValue(float $saleValue): static
    {
        $this->saleValue = $saleValue;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getValidTill(): ?\DateTimeImmutable
    {
        return $this->validTill;
    }

    public function setValidTill(?\DateTimeImmutable $validTill): static
    {
        $this->validTill = $validTill;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): static
    {
        $this->purchase = $purchase;
        return $this;
    }
}
