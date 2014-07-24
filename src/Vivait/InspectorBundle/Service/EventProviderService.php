<?php

namespace Vivait\InspectorBundle\Service;

use Metadata\MetadataFactoryInterface;
use Vivait\Voter\Model\EntityEvent;

class EventProviderService
{
    protected function locateEventClasses($eventNamespace, $eventPath) {
        $finder = new \Metadata\Driver\FileLocator([
            $eventNamespace => $eventPath
          ]);
        return $finder->findAllClasses('php');
    }

    public function getEvents($eventNamespace, $eventPath) {
        return $this->filterEntityEvents($this->locateEventClasses($eventNamespace, $eventPath));
    }

    /**
     * @param EntityEvent[] $classes
     * @return array
     */
    protected function filterEntityEvents(array $classes) {
        $events = [];
        foreach ($classes as $class) {
            if (is_a($class, 'Vivait\Voter\Model\EntityEvent', true)) {
                $events += $class::supportsEvents();
            }
        }

        return $events;
    }
}