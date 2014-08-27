<?php

namespace Vivait\InspectorBundle\EventListener;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Model\InspectionProviderInterface;
use Vivait\InspectorBundle\Service\Inspection\RegisterInspection;

class LoadInspectionsListener
{
    /**
     * @var RegisterInspection
     */
    private $registerInspection;

    /**
     * @var InspectionProviderInterface
     */
    private $provider;

    function __construct(
      InspectionProviderInterface $provider,
      RegisterInspection $registerInspection
    ) {

        $this->registerInspection = $registerInspection;
        $this->provider = $provider;
    }

    public function registerInspections()
    {
        foreach ($this->provider->getInspections() as $inspection) {
            $this->registerInspection->registerInspection($inspection->getEventName(), $inspection->getId(), $inspection->getName());
        }

        return true;
    }
}