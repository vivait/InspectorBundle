<?php

namespace Vivait\InspectorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vivait\InspectorBundle\DependencyInjection\VoterCompilerPass;

class VivaitInspectorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new VoterCompilerPass());
    }
}
