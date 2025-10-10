<?php

namespace App\Controller;

use App\DTO\Http\Request\MakePurchase\MakePurchaseRequest;
use App\Entity\Purchase;
use App\Enum\PaymentSystemType;
use App\Exception\CouponException;
use App\Exception\PaymentException;
use App\Facade\PaymentFacade;
use App\Facade\TaxFacade;
use App\Repository\ProductRepository;
use App\Response\ApiResponse;
use App\Response\ErrorResponse;
use App\Service\Coupon\CouponService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Uid\UuidV7;

final class MakePurchaseController extends AbstractController
{
    public function __construct(
        protected TaxFacade $taxFacade,
        protected PaymentFacade $paymentFacade,
        protected PaymentSystemType $defaultPaymentSystem,
        protected ProductRepository $productRepository,
        protected CouponService $couponService,
    ) {
    }

    #[Route('/purchase', name: 'purchase', methods: [Request::METHOD_POST])]
    public function __invoke(
        #[MapRequestPayload] MakePurchaseRequest $requestData
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
            paymentSystem: $requestData->paymentProcessor ?? $this->defaultPaymentSystem,
        );

        if ($requestData->couponCode !== null) {
            try {
                $this->couponService->applyCoupon($purchase, $requestData->couponCode);
            } catch (CouponException $ce) {
                return ErrorResponse::build($ce->getMessage());
            }
        }

        try {
            $receipt = $this->paymentFacade->processPurchase($purchase);
        } catch (PaymentException|CouponException $e) {
            return ErrorResponse::build($e->getMessage());
        }

        return ApiResponse::build($receipt);
    }
}
