<?php

namespace App\Controller;

use App\DTO\Http\Request\CalculatePrice\CalculatePriceRequest;
use App\DTO\Http\Response\CalculatePrice\CalculatePriceResponse;
use App\Entity\Purchase;
use App\Enum\PaymentSystemType;
use App\Exception\CouponException;
use App\Facade\TaxFacade;
use App\Repository\ProductRepository;
use App\Response\ApiResponse;
use App\Response\ErrorResponse;
use App\Service\Coupon\CouponService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Uid\UuidV7;

final class CalculatePriceController extends AbstractController
{
    public function __construct(
        protected TaxFacade $taxFacade,
        protected PaymentSystemType $defaultPaymentSystem,
        protected ProductRepository $productRepository,
        protected CouponService $couponService,
    ) {
    }

    #[Route('/calculate-price', name: 'calculate_price')]
    public function __invoke(
        #[MapRequestPayload] CalculatePriceRequest $requestData
    ): JsonResponse {
        $product = $this->productRepository->find($requestData->product);
        if ($product === null) {
            return ErrorResponse::build('Invalid product');
        }

        $purchaser = $this->taxFacade->getUserByTaxCode($requestData->taxNumber);
        if ($purchaser === null) {
            return ErrorResponse::build('Invalid taxNumber');
        }

        $purchase = new Purchase(
            id: new UuidV7(),
            purchaser: $purchaser,
            product: $product,
            paymentSystem: $this->defaultPaymentSystem,
        );

        if ($requestData->couponCode !== null) {
            try {
                $this->couponService->applyCoupon($purchase, $requestData->couponCode);
            } catch (CouponException $ce) {
                return ErrorResponse::build($ce->getMessage());
            }
        }

        try {
            $tax = $this->taxFacade->calculatePurchaseTax($purchase);
            $sale = $this->couponService->calculatePurchaseSale($purchase);
        } catch (CouponException $ce) {
            return ErrorResponse::build($ce->getMessage());
        }

        $price = $purchase->getGrossTotal() - $sale + $tax;

        $responseData = new CalculatePriceResponse($price);
        return ApiResponse::build($responseData);
    }
}
