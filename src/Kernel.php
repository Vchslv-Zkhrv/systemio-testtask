<?php

namespace App;

use App\DependencyInjection\Compiler\PaymentServiceCompilerPass;
use App\DependencyInjection\Compiler\TaxServiceCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TaxServiceCompilerPass());
        $container->addCompilerPass(new PaymentServiceCompilerPass());
    }
}
