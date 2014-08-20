<?php

namespace Vivait\InspectorBundle\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Vivait\Common\Container\Service\LoaderService;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Model\ActionDispatcherFactory;
use Vivait\InspectorBundle\Service\RegisterService;
use Vivait\Voter\Dispatcher\ActionDispatcher;
use Vivait\Voter\Model\EntityEvent;
use Vivait\InspectorBundle\Entity\Inspection;

class LoadInspectionsListener
{
    /**
     * @var string
     */
    private $cachePath;
    /**
     * @var RegisterService
     */
    private $registerService;

    function __construct(
      $cachePath,
      RegisterService $registerService,
      LoggerInterface $logger = null
    ) {
        if (!$logger) {
            $logger = new NullLogger();
        }

        $this->logger = $logger;
        $this->cachePath = $cachePath;
        $this->registerService = $registerService;
    }

    public function registerInspectionsFromCacheEvent(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return false;
        }

        return $this->registerInspectionsFromCache();
    }

    public function registerInspectionsFromCache()
    {
        if (!file_exists($this->cachePath)) {
            $this->logger->warning('No cache found for inspections');

            return false;
        }

        $map = include($this->cachePath);

        foreach ($map as $eventName => $inspections) {
            foreach ($inspections as $id => $inspectionName) {
                $this->registerService->registerInspection($eventName, $id, $inspectionName);
            }
        }

        return true;
    }
}