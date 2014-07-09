<?php

namespace Vivait\InspectorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class VoterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('vivait_inspector.voter.registry')) {
            return;
        }

        $definition = $container->getDefinition(
          'vivait_inspector.voter.registry'
        );

        $taggedServices = $container->findTaggedServiceIds(
          'vivait_inspector.voter'
        );
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                  'addVoter',
                  [
                    $attributes['label'],
                    new Reference($id)
                  ]
                );
            }
        }
    }
}