<?php

namespace Vivait\InspectorBundle\Model;


use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Vivait\Common\Container\Service\LoaderService;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\InspectorBundle\Entity\InspectionRepository;
use Vivait\InspectorBundle\Service\Voter\VoterRegistry;
use Vivait\Voter\Dispatcher\ActionDispatcher;

class ActionDispatcherFactory
{
    /**
     * @var InspectionRepository
     */
    private $inspectionRepository;

    /**
     * @var LoaderService
     */
    private $serviceLoader;

    /**
     * @var \Vivait\InspectorBundle\Service\Voter\VoterRegistry
     */
    private $voterRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    function __construct(
      InspectionRepository $inspectionRepository,
      LoaderService $serviceLoader,
      VoterRegistry $voterRegistry,
      LoggerInterface $logger = null
    ) {
        $this->inspectionRepository = $inspectionRepository;
        $this->serviceLoader = $serviceLoader;

        if (!$logger) {
            $logger = new NullLogger();
        }

        $this->logger = $logger;
        $this->voterRegistry = $voterRegistry;
    }

    public function create($inspection) {
        if (!($inspection instanceOf Inspection)) {
            $inspection = $this->inspectionRepository->find($inspection);
        }

        return $this->loadActionDispatcher($inspection);
    }

    /**
     * @param Inspection $inspection
     * @return ActionDispatcher
     * @throws \Exception
     */
    protected function loadActionDispatcher(Inspection $inspection)
    {
        $actions = $this->serviceLoader->loadServices($inspection->getActions());

        $votertype = $inspection->getVoterType();

        $voter = $this->voterRegistry->getVoter($votertype);
        $voter->addConditions($this->serviceLoader->loadServices($inspection->getConditions()));

        return new ActionDispatcher($inspection->getName(), $voter, $actions, $this->logger);
    }
}