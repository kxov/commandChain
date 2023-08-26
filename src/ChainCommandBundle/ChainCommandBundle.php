<?php

declare(strict_types=1);

namespace App\ChainCommandBundle;

use App\ChainCommandBundle\DependencyInjection\ChainCommandBundleExtension;
use App\ChainCommandBundle\DependencyInjection\Compiler\ChainCommandPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChainCommandBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new ChainCommandPass(), PassConfig::TYPE_OPTIMIZE, -1);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ChainCommandBundleExtension();
    }
}