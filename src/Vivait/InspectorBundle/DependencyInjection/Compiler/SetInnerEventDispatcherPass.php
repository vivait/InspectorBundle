<?php

namespace Vivait\InspectorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class SetInnerEventDispatcherPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasAlias('event_dispatcher')) {
            $container->setAlias('vivait_inspector.event_dispatcher.inner', new Alias((string) $container->getAlias('event_dispatcher'), false));
        } else {
            $definition = $container->getDefinition('event_dispatcher');
            $definition->setPublic(false);
            $container->setDefinition('vivait_inspector.event_dispatcher.inner', $definition);
        }
    }
}
