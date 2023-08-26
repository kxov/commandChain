<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Provide pass for add all tagged commands to service
 */
class ChainCommandPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has('chain.command.service')) {
            return;
        }

        $chainCommandService = $container->findDefinition('chain.command.service');

        // find all service IDs with the chain_command tag
        $chainCommands = $container->findTaggedServiceIds('chain_command');

        foreach ($chainCommands as $id => $tags) {
            foreach ($tags as $attributes) {
                // add the chain command to the ChainCommand service
                $chainCommandService->addMethodCall('addCommand', [new Reference($id), $attributes['head']]);
            }
        }
    }
}
