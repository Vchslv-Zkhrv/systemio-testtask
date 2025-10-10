<?php

namespace App\DependencyInjection\Compiler;

use App\DependencyInjection\Collection\PaymentServiceCollection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collects all services by `app.tax_service` tag into TaxFacade `$taxServices` constructor argument
 */
class PaymentServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(PaymentServiceCollection::class);

        $paymentServicesDefinitions = $container->findTaggedServiceIds('app.payment_service');

        foreach (array_keys($paymentServicesDefinitions) as $paymentServiceId) {
            $definition->addMethodCall(
                'add',
                [
                    $paymentServiceId::getPaymentSystem()->value,
                    new Reference($paymentServiceId)
                ]
            );
        }
    }
}
