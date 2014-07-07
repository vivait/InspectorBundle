<?php

namespace Vivait\InspectorBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vivait\Common\Container\Service\LoaderService;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\Voter\Dispatcher\ActionDispatcher;
use Vivait\Voter\Model\EntityEvent;
use Vivait\InspectorBundle\Entity\Inspection;

class RegisterService
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var EntityManagerInterface
     */
    private $entity_manager;

    /**
     * @var LoaderService
     */
    private $serviceloader;

    /**
     * @var LoggerInterface
     */
    private $logger;

    function __construct(
      EventDispatcherInterface $dispatcher,
      EntityManagerInterface $entity_manager,
      LoaderService $serviceloader,
      LoggerInterface $logger = null
    ) {
        $this->dispatcher     = $dispatcher;
        $this->entity_manager = $entity_manager;
        $this->serviceloader  = $serviceloader;

        if (!$logger) {
            $logger = new NullLogger();
        }

        $this->logger = $logger;
    }

    /**
     * @return Inspection[]
     */
    public function fetchInspections()
    {
        return $this->entity_manager->getRepository('VivaitInspectorBundle:Inspection')->findAll();
    }

    /**
     * @param Inspection $inspection
     * @return ActionDispatcher
     * @throws \Exception
     */
    public function actionDispatcherFactory(Inspection $inspection)
    {
        $actions = $this->serviceloader->loadServices($inspection->getActions());

        $voter = $inspection->getVoter();
        $voter->addConditions($this->serviceloader->loadServices($inspection->getConditions()));

        return new ActionDispatcher($inspection->getName(), $voter, $actions, $this->logger);
    }

    /**
     * @param Inspection[]|Inspection $inspections
     * @return $this
     */
    public function registerInspections($inspections)
    {
        if (is_array($inspections)) {
            foreach ($inspections as $row) {
                $this->registerInspections($row);
            }
        } else {
            $eventName = $inspections->getEventName();
            $inspectionName = $inspections->getName();

            $handler = $this->actionDispatcherFactory($inspections);

            $this->logger->debug(sprintf('Adding inspection "%s" for event "%s"', $inspectionName, $eventName));

            $this->dispatcher->addListener(
              $eventName,
              function (EntityEvent $event, $eventName) use ($handler, $inspectionName) {
                  $this->logger->info(sprintf('Calling inspection "%s" for event "%s"', $inspectionName, $eventName));
                  $handler->perform($event->provides());
              }
            );
        }

        return $this;
    }

    /**
     * Gets logger
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Sets logger
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }
} 