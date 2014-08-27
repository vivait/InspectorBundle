<?php

namespace Vivait\InspectorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ReplaceEventDispatcherPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setDefinition('event_dispatcher', $container->getDefinition('vivait_inspector.event_dispatcher'));
        $container->removeDefinition('vivait_inspector.event_dispatcher');
    }
}
