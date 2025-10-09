<?php

namespace App\DependencyInjection\Compiler;

use App\DependencyInjection\Collection\TaxServiceCollection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collects all services by `app.tax_service` tag into TaxFacade `$taxServices` constructor argument
 */
class TaxServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(TaxServiceCollection::class);

        $taxServicesDefinitions = $container->findTaggedServiceIds('app.tax_service');
        $taxServices = [];

        foreach (array_keys($taxServicesDefinitions) as $taxServiceId) {
            $definition->addMethodCall(
                'add',
                [
                    $taxServiceId::getCountryCode(),
                    new Reference($taxServiceId)
                ]
            );
        }
    }
}
