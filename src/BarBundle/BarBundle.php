<?php

declare(strict_types=1);

namespace App\BarBundle;

use App\BarBundle\DependencyInjection\BarBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BarBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BarBundleExtension();
    }
}