<?php

namespace Vivait\InspectorBundle\EventListener;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Entity\Inspection;
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
        try {
            if (!$this->provider) {
                throw new \RuntimeException('No inspection provider has been specified');
            }

            foreach ($this->provider->getInspections() as $id => $inspection) {
                if ($inspection instanceOf Inspection) {
                    $this->registerInspection->registerInspection($inspection->getEventName(), $inspection->getId(), $inspection->getName());
                }
                else {
                    $this->registerInspection->registerInspection($inspection['eventName'], $id, $inspection['name']);
                }
            }
        }
        catch (\Exception $e) {
            var_dump($e);
        }

        return true;
    }
}