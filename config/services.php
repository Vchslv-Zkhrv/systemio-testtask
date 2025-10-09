<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\DependencyInjection\Collection\TaxServiceCollection;
use App\Enum\PaymentSystemType;
use App\Facade\TaxFacade;
use App\Service\Payment\PaymentService;
use App\Service\Payment\PaypalPaymentService;
use App\Service\Payment\StripePaymentService;
use App\Service\Tax\FranceTaxService;
use App\Service\Tax\GreeceTaxService;
use App\Service\Tax\GermanyTaxService;
use App\Service\Tax\ItalyTaxService;
use App\Service\Tax\TaxService;

return static function (ContainerConfigurator $container): void {
    $paymentSystem = PaymentSystemType::from(getenv('PAYMENT_SYSTEM') ?: 'paypal');

    $services = $container->services();
    $defaults = $services->defaults();

    $defaults->autowire()->autoconfigure();
    $defaults->bind('$paymentSystemType', $paymentSystem);

    $services->instanceof(TaxService::class)->tag('app.tax_service');
    $services->set(GermanyTaxService::class)->args(['$vat' => '%env(GERMAN_VAT)%'])->autowire()->public();
    $services->set(ItalyTaxService::class)->args(['$vat' => '%env(ITALY_VAT)%'])->autowire()->public();
    $services->set(FranceTaxService::class)->args(['$vat' => '%env(FRANCE_VAT)%'])->autowire()->public();
    $services->set(GreeceTaxService::class)->args(['$vat' => '%env(GREECE_VAT)%'])->autowire()->public();
    $services->set(TaxServiceCollection::class)->autowire()->public();
    $services->set(TaxFacade::class)->autowire()->public();

    $services->load('App\\Command\\',      '../src/Command');
    $services->load('App\\Controller\\',   '../src/Controller');
    $services->load('App\\DataFixtures\\', '../src/DataFixtures');
    $services->load('App\\Facade\\',       '../src/Facade');
    $services->load('App\\Repository\\',   '../src/Repository');
    $services->load('App\\Service\\',      '../src/Service')->exclude([
        '../src/Service/Tax/*',
    ]);

    $paymentServiceId = match ($paymentSystem) {
        PaymentSystemType::PAYPAL => PaypalPaymentService::class,
        PaymentSystemType::STRIPE => StripePaymentService::class,
    };
    $services->alias(PaymentService::class, $paymentServiceId);
};
