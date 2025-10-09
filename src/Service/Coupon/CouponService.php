<?php

namespace App\Service\Coupon;

use App\Entity\Coupon;
use App\Entity\Purchase;
use App\Entity\User;
use App\Enum\PurchaseStatusType;
use App\Enum\SaleType;
use App\Exception\CouponException;
use App\Repository\CouponRepository;

class CouponService
{
    public function __construct(protected CouponRepository $couponRepository)
    {
    }

    public function getUserCoupon(User $user, string $code): ?Coupon
    {
        return $this->couponRepository->findOneBy([
            'receiver' => $user,
            'code' => $code,
        ]);
    }

    public function applyCoupon(
        Purchase $purchase,
        string $couponCode
    ): Coupon {
        $coupon = $this->getUserCoupon($purchase->getPurchaser(), $couponCode);

        if ($coupon === null) {
            throw new CouponException('Invalid couponCode');
        }

        if ($coupon->getValidTill() !== null && $coupon->getValidTill() < new \DateTime('now')) {
            throw new CouponException('Coupon expired');
        }

        if (($couponPurchase = $coupon->getPurchase()) !== null) {
            if ($couponPurchase->getStatus() == PurchaseStatusType::SUCCESS) {
                throw new CouponException('Used coupon');
            }
            $couponPurchase->removeCoupon($coupon);
        }

        $purchase->addCoupon($coupon);
        return $coupon;
    }

    public static function calculatePurchaseSale(Purchase $purchase): float
    {
        $gross = $purchase->getGrossTotal();
        if ($gross <= 0) {
            throw new CouponException("Cannot apply sales on zero price");
        }

        $coupons = $purchase->getCoupons();
        $sale = 0;

        foreach ($coupons as $coupon) {
            if ($coupon->getSaleType() == SaleType::PERCENT) {
                $sale += $gross * ($coupon->getSaleValue() / 100);
            } elseif ($coupon->getSaleType() == SaleType::EXACT) {
                $sale += $coupon->getSaleValue();
            }
        }

        if ($sale > $gross) {
            return new CouponException("Cannot apply coupons: sale is bigger than price");
        }

        return $sale;
    }
}
