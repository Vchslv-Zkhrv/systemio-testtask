<?php

namespace App\Entity;

use App\Enum\PaymentSystemType;
use App\Enum\PurchaseStatusType;
use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\Table('purchases')]
class Purchase
{
    #[ORM\Id]
    #[ORM\Column('id', type: 'uuid')]
    private Uuid $id;

    #[ORM\ManyToOne(User::class, inversedBy: 'purchases')]
    #[ORM\JoinColumn('purchaser_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private User $purchaser;

    #[ORM\Column('created_at')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column('payment_system', type: 'payment_system_enum')]
    private PaymentSystemType $paymentSystem;

    #[ORM\Column('status', type: 'purchase_status_enum')]
    private PurchaseStatusType $status;

    /**
     * @var Collection<int, Coupon>
     */
    #[ORM\OneToMany(targetEntity: Coupon::class, mappedBy: 'purchase')]
    private Collection $coupons;

    public function __construct(
        Uuid $id,
        User $purchaser,
        PaymentSystemType $paymentSystem,
        PurchaseStatusType $status,
        ?\DateTimeImmutable $createdAt = null,
    ) {
        $this->id = $id;
        $this->purchaser = $purchaser;
        $this->paymentSystem = $paymentSystem;
        $this->status = $status;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->coupons = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPurchaser(): User
    {
        return $this->purchaser;
    }

    public function setPurchaser(User $purchaser): static
    {
        $this->purchaser = $purchaser;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPaymentSystem(): PaymentSystemType
    {
        return $this->paymentSystem;
    }

    public function getStatus(): PurchaseStatusType
    {
        return $this->status;
    }

    public function setStatus(PurchaseStatusType $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Collection<int, Coupon>
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function addCoupon(Coupon $coupon): static
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons->add($coupon);
            $coupon->setPurchase($this);
        }

        return $this;
    }

    public function removeCoupon(Coupon $coupon): static
    {
        if ($this->coupons->removeElement($coupon)) {
            // set the owning side to null (unless already changed)
            if ($coupon->getPurchase() === $this) {
                $coupon->setPurchase(null);
            }
        }

        return $this;
    }
}
