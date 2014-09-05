<?php

namespace Vivait\InspectorBundle\Service\Inspection;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Model\ActionDispatcherFactory;
use Vivait\Voter\Dispatcher\ActionDispatcher;
use Vivait\Voter\Dispatcher\ActionDispatcherInterface;
use Vivait\Voter\Dispatcher\LazyActionDispatcher;
use Vivait\InspectorBundle\Entity\Inspection;

class RegisterInspection
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ActionDispatcherFactory
     */
    private $actionDispatcherFactory;

    function __construct(
      ActionDispatcherFactory $actionDispatcherFactory,
      EventDispatcherInterface $dispatcher = null,
      LoggerInterface $logger = null
    ) {
        $this->dispatcher = $dispatcher;

        if (!$logger) {
            $logger = new NullLogger();
        }

        $this->logger = $logger;
        $this->actionDispatcherFactory = $actionDispatcherFactory;
    }

    /**
     * @param string            $eventName The event name to listen to
     * @param string|Inspection $inspection An inspection object or inspection ID to lazy load the inspection
     * @param string            $inspectionName The inspection name, if an ID is passed as $inspection
     * @return $this
     */
    public function registerInspection($eventName, $inspection, $inspectionName = null)
    {
        if (!$this->dispatcher) {
            throw new \LogicException('No dispatcher specified when trying to register an inspection event');
        }

        if (!($inspection instanceOf Inspection)) {
            if ($inspectionName === null) {
                throw new \InvalidArgumentException('An inspection name must be provided for lazy loaded inspections');
            }

            $actionDispatcher = new LazyActionDispatcher($inspection, $inspectionName, $this->actionDispatcherFactory);
        } else {
            $actionDispatcher = $this->actionDispatcherFactory->create($inspection);
        }

        $this->registerActionDispatcher($eventName, $actionDispatcher);

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

    /**
     * @param                  $eventName
     * @param ActionDispatcher $actionDispatcher
     * @return $this
     */
    public function registerActionDispatcher($eventName, ActionDispatcherInterface $actionDispatcher)
    {
        $this->logger->debug(
          sprintf('Adding inspection "%s" for event "%s"', $actionDispatcher->getName(), $eventName)
        );

        $this->dispatcher->addListener(
          $eventName,
          [$actionDispatcher, 'performFromEvent']
        );

        return $this;
    }

    /**
     * Sets dispatcher
     * @param EventDispatcherInterface $dispatcher
     * @return $this
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }
}