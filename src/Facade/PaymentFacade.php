<?php

namespace App\Facade;

use App\DTO\Payment\ReceiptDTO;
use App\DTO\Payment\ReceiptProductDTO;
use App\DependencyInjection\Collection\PaymentServiceCollection;
use App\Entity\Purchase;
use App\Exception\PaymentException;
use App\Service\Coupon\CouponService;
use Doctrine\ORM\EntityManagerInterface;

class PaymentFacade
{
    public function __construct(
        protected PaymentServiceCollection $paymentServices,
        protected TaxFacade $taxFacade,
        protected EntityManagerInterface $em,
    ) {
    }

    public function processPurchase(Purchase $purchase): ReceiptDTO
    {
        $taxCode = $purchase->getPurchaser()->getTaxCode();
        if ($taxCode === null) {
            throw new PaymentException("Purchaser's tax code isn't specified");
        }

        $product = $purchase->getProduct();
        if ($product->getStock() < $purchase->getQuantity()) {
            throw new PaymentException("Not enough products in stock");
        }

        $tax = $this->taxFacade->calculatePurchaseTax($purchase);
        $sale = CouponService::calculatePurchaseSale($purchase);
        $price = $purchase->getGrossTotal() - $sale + $tax;

        $paymentService = $this->paymentServices->get($purchase->getPaymentSystem());

        $paymentService->processPurchase($purchase);
        $this->em->persist($purchase);

        $product->setStock($product->getStock() - $purchase->getQuantity());
        $this->em->persist($product);

        $this->em->flush();

        return new ReceiptDTO(
            taxNumber: $taxCode,
            products: [
                new ReceiptProductDTO(
                    article: $product->getArticle(),
                    name: $product->getName(),
                    quantity: $purchase->getQuantity()
                ),
            ],
            grossPrice: $purchase->getGrossTotal(),
            tax: $tax,
            sale: $sale,
            totalPrice: $price,
            paymentProcessor: $purchase->getPaymentSystem(),
        );
    }
}
