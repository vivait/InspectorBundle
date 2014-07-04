<?php

namespace Vivait\InspectorBundle\Entity\Action;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\Voter\Model\ActionInterface;

class TestActionService implements ActionInterface
{
    public function requires()
    {
        // TODO: Implement requires() method.
    }

    public function perform($entity)
    {
        // TODO: Implement perform() method.
    }
}
