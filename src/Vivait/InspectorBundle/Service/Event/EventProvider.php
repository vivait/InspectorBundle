<?php

namespace Vivait\InspectorBundle\Service\Event;

use Metadata\MetadataFactoryInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\DirectoryResource;
use Vivait\Voter\Model\EntityEvent;

/**
 * Scans the register locations for compatible event classes
 */
class EventProvider
{
    /**
     * @var string[]
     */
    private $eventLocations;

    function __construct(array $eventLocations)
    {
        $this->eventLocations = $eventLocations;
    }

    protected function locateEventClasses($eventNamespace, $eventPath) {
        $finder = new \Metadata\Driver\FileLocator([
            $eventNamespace => $eventPath
          ]);
        return $finder->findAllClasses('php');
    }

    public function getEvents() {
        $events = [];
        foreach ($this->eventLocations as $namespace => $location) {
            $events += $this->locateEventClasses($namespace, $location);
        }

        return $this->filterEntityEvents($events);
    }

    /**
     * @param EntityEvent[] $classes
     * @return array
     */
    protected function filterEntityEvents(array $classes) {
        $events = [];
        foreach ($classes as $class) {
            if (is_a($class, 'Vivait\Voter\Model\EntityEvent', true)) {
                $events += (array)$class::supportsEvents();
            }
        }

        return $events;
    }
}